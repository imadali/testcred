<?php
/**
 * This file is part of the SetaPDF-Merger Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Merger
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: Merger.php 831 2016-03-01 08:01:25Z jan.slabon $
 */

/**
 * The main class of the SetaPDF-Merger Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Merger
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Merger
{
    /**
     * Version
     *
     * @var string
     */
    const VERSION = '2.6.4.864';

    /**
     * Constant defines that existing outline items should be copied as child items to the newly created outline item
     *
     * @var string
     */
    const COPY_OUTLINES_AS_CHILDS = 'copyOutlinesAsChilds';

    /**
     * Constant defines that existing outlines items should be copied to the outlines root
     *
     * @var string
     */
    const COPY_OUTLINES_TO_ROOT = 'copyOutlinesToRoot';

    /**
     * Key for the title property of an outline item
     *
     * @var string
     */
    const OUTLINES_TITLE = SetaPDF_Core_Document_OutlinesItem::TITLE;

    /**
     * Key for the color property of an outline item
     *
     * @var string
     */
    const OUTLINES_COLOR = SetaPDF_Core_Document_OutlinesItem::COLOR;

    /**
     * Key for the bold style property of an outline item
     *
     * @var string
     */
    const OUTLINES_BOLD = SetaPDF_Core_Document_OutlinesItem::BOLD;

    /**
     * Key for the italic style property of an outline item
     *
     * @var string
     */
    const OUTLINES_ITALIC = SetaPDF_Core_Document_OutlinesItem::ITALIC;

    /**
     * Key for the parent property of an outline item
     *
     * @var string
     */
    const OUTLINES_PARENT = 'parent';

    /**
     * Key for the copy behavior of an outline item
     *
     * @var string
     */
    const OUTLINES_COPY = 'copy';

    /**
     * Keyword for all pages
     *
     * @var string
     */
    const PAGES_ALL = 'all';

    /**
     * Keyword for the first page
     *
     * @var string
     */
    const PAGES_FIRST = 'first';

    /**
     * Keyword for the last page
     *
     * @var string
     */
    const PAGES_LAST = 'last';

    /**
     * The initial document
     *
     * The initial document is the document to which the
     * new documents/pages will be added.
     *
     * It will be created automatically if none was provided
     * in the constructor.
     *
     * @var SetaPDF_Core_Document
     */
    protected $_initialDocument;

    /**
     * The currently processed document instance.
     *
     * @var SetaPDF_Core_Document
     */
    protected $_currentDocument;

    /**
     * The documents/pages which should be added
     *
     * @var array
     */
    protected $_documents = array();

    /**
     * Cache for document objects by filename
     *
     * @var array
     */
    protected $_documentCache = array();

    /**
     * Should names be copied/handled
     *
     * @var boolean
     */
    protected $_handleNames = true;

    /**
     * Callback method used for renaming names
     *
     * @see SetaPDF_Core_DataStructure_NameTree::adjustNameCallback()
     * @var callback
     */
    protected $_adjustNameCallback = null;

    /**
     * Renamed names
     *
     * @internal
     * @var array
     */
    protected $_renamed = array();

    /**
     * An array to save information about changed form fields
     *
     * @var array
     */
    public $rewrittenFormFieldNamesData = array();

    /**
     * Flag saying if same named form fields should be renamed.
     *
     * @var bool
     */
    protected $_renameSameNamedFormFields = true;

    /**
     * A callback which is called just before a page is added to the new document
     *
     * @var null|callback
     * @see SetaPDF_Merger::_beforePageAdded()
     */
    public $beforePageAddedCallback = null;

    /**
     * The constructor.
     *
     * @param SetaPDF_Core_Document $initialDocument The initial document to start with
     */
    public function __construct(SetaPDF_Core_Document $initialDocument = null)
    {
        $this->_initialDocument = $initialDocument;
        $this->_adjustNameCallback = array('SetaPDF_Core_DataStructure_NameTree', 'adjustNameCallback');
    }

    /**
     * Returns the initial document.
     *
     * @see SetaPDF_Merger::$_initialDocument
     * @return SetaPDF_Core_Document
     */
    public function getInitialDocument()
    {
        if (null === $this->_initialDocument)
            $this->_initialDocument = new SetaPDF_Core_Document();

        return $this->_initialDocument;
    }

    /**
     * Alias for getInitialDocument.
     *
     * @return SetaPDF_Core_Document
     */
    public function getDocument()
    {
        return $this->getInitialDocument();
    }

    /**
     * Set the writer for the initial document.
     *
     * @param SetaPDF_Core_Writer_WriterInterface $writer The writer instance
     */
    public function setWriter(SetaPDF_Core_Writer_WriterInterface $writer)
    {
        $this->getInitialDocument()->setWriter($writer);
    }

    /**
     * Helper method to get the page count of a document or file.
     *
     * @param string|SetaPDF_Core_Document $filename The filename or the document instance
     * @param boolean $cacheDocumentInstance Cache the document instance or not
     * @return integer
     */
    public function getPageCount($filename, $cacheDocumentInstance = true)
    {
        $document = $this->_getDocument($filename, $cacheDocumentInstance);
        $pages = $document->getCatalog()->getPages();

        return $pages->count();
    }

    /**
     * Add a document by filename.
     *
     * The document could include dynamic content like form fields, links or any other page annotation.
     *
     * Form fields are handled especially:
     * If a document was added with form fields which names were already used by a previously added
     * document the field name will be suffixed with a slash and a number.
     *
     * This behavior may lead to corrupted java scripts which may calculate field sums by field names!
     *
     * @param string|array $filenameOrConfig The filename or config array. If an array is passed the keys has to be
     *                                       named as the method parameters. All other parameters are optional then.
     * @param mixed $pages                   The pages to add from the file. See
     *                                       {@link SetaPDF_Merger::_checkPageNumber() _checkPageNumber()} for full
     *                                       description.
     * @param string $name The name for a named destination for this file
     * @param null|string|array $outlinesConfig The outlines config
     * @param boolean $copyLayers Whether to copy layer information of the document
     *
     * @throws InvalidArgumentException
     * @return int|null
     */
    public function addFile($filenameOrConfig, $pages = null, $name = null, $outlinesConfig = null, $copyLayers = true)
    {
        if (is_array($filenameOrConfig)) {
            if (!isset($filenameOrConfig['filename'])) {
                throw new InvalidArgumentException('Missing filename-key in config array.');
            }

            foreach ($filenameOrConfig AS $key => $value) {
                $$key = $value;
            }

            /**
             * @var string $filename
             */
        } else {
            $filename = $filenameOrConfig;
        }

        $this->_documents[] = array($filename, $pages, $name, $outlinesConfig, $copyLayers);

        return $this->_checkOutlinesConfig($outlinesConfig);
    }

    /**
     * Add a document.
     *
     * Same as {@link SetaPDF_Merger::addFile() addFile()} but the document has to be passed as
     * {@link SetaPDF_Core_Document} instance.
     *
     * @see addFile()
     *
     * @param SetaPDF_Core_Document|array $documentOrConfig The document or config array. If an array is passed the keys
     *                                                      has to be named as the method parameters. All other
     *                                                      parameters are optional then.
     * @param mixed $pages                                  The pages to add from the file. See
     *                                                      {@link SetaPDF_Merger::_checkPageNumber() _checkPageNumber()}
     *                                                      for full description.
     * @param string $name The name for a named destination for this document
     * @param null|string|array $outlinesConfig The outlines config
     * @param boolean $copyLayers Whether to copy layer information of the document
     *
     * @throws InvalidArgumentException
     * @return int|null
     */
    public function addDocument(
        $documentOrConfig, $pages = null, $name = null, $outlinesConfig = null, $copyLayers = true
    )
    {
        if (is_array($documentOrConfig)) {
            if (!isset($documentOrConfig['document'])) {
                throw new InvalidArgumentException('Missing document-key in config array.');
            }

            foreach ($documentOrConfig AS $key => $value) {
                $$key = $value;
            }

            /**
             * @var $document
             */
        } else {
            $document = $documentOrConfig;
        }

        if (!($document instanceof SetaPDF_Core_Document)) {
            throw new InvalidArgumentException('Invalid $document parameter. Has to be instance of SetaPDF_Core_Document');
        }

        $this->_documents[] = array($document, $pages, $name, $outlinesConfig, $copyLayers);

        return $this->_checkOutlinesConfig($outlinesConfig);
    }

    /**
     * Checks the $outlinesConfig parameter if it is possible to add childs to the resulting outline item.
     *
     * @param string|array $outlinesConfig The outlines config
     * @return int|null
     */
    protected function _checkOutlinesConfig($outlinesConfig)
    {
        // only return an id if outline is added and is usable as a parent item
        return
            $outlinesConfig !== null && (
                is_string($outlinesConfig) || is_array($outlinesConfig) && isset($outlinesConfig[self::OUTLINES_TITLE])
            )
            ? count($this->_documents) - 1
            : null;
    }

    /**
     * Will be called just before a page is added to the pages tree.
     *
     * An own callback can be defined through the $beforePageAddedCallback property.
     * Or this method can be overwritten to implement own logic in the scope of the class.
     *
     * @param SetaPDF_Core_Document_Page $page The page that will be added
     * @param int $pageNumber The number of the page
     */
    protected function _beforePageAdded(SetaPDF_Core_Document_Page $page, $pageNumber)
    {
        if ($this->beforePageAddedCallback !== null && is_callable($this->beforePageAddedCallback)) {
            call_user_func($this->beforePageAddedCallback, $page, $pageNumber);
        }
    }

    /**
     * Defines that the document's name dictionaries are merged into the resulting document.
     *
     * This behavior is enabled by default. It sadly needs much memory and script runtime,
     * because name trees could be very huge.
     *
     * @param boolean $handleNames The flag status
     * @param null|callback $adjustNameCallback See {@link SetaPDF_Core_DataStructure_Tree::merge()} for a detailed description of the callback
     */
    public function setHandleNames($handleNames = true, $adjustNameCallback = null)
    {
        $this->_handleNames = (boolean)$handleNames;
        if (null !== $adjustNameCallback) {
            $this->_adjustNameCallback = $adjustNameCallback;
        }
    }

    /**
     * Set the flag defining if same named form fields should be renamed (default behavior).
     *
     * If this flag is set to false the fields will be merged so that all same named fields
     * will have the same value. Notice that this could occur in an incorrect appearance if the
     * initial values are different.
     *
     * @param bool $renameSameNamedFormFields The flag status
     */
    public function setRenameSameNamedFormFields($renameSameNamedFormFields = true)
    {
        $this->_renameSameNamedFormFields = $renameSameNamedFormFields;
    }

    /**
     * Merges the documents/pages in memory.
     *
     * This method merges the documents and/or pages to the initial
     * document object without calling the save()-method.
     * The document is hold in memory until it is "manually" saved through the
     * initial document instance.
     *
     * @return SetaPDF_Core_Document
     * @throws SetaPDF_Core_SecHandler_Exception
     * @throws SetaPDF_Merger_Exception
     */
    public function merge()
    {
        $resDocument = $this->getInitialDocument();
        $resPages = $resDocument->getCatalog()->getPages();

        $touchedPdfs = array();
        $addedPages = array();

        $namedDestinations = array();
        $outlineTargets = array();

        foreach ($this->_documents AS $currentDocumentId => $documentData) {
            $this->_currentDocument = null;
            $this->_currentDocument = $this->_getDocument($documentData[0]);

            if ($this->_currentDocument->hasSecHandler()) {
                $secHandler = $this->_currentDocument->getSecHandlerIn();
                if (!$secHandler->getPermission(SetaPDF_Core_SecHandler::PERM_ASSEMBLE)) {
                    throw new SetaPDF_Core_SecHandler_Exception(
                        sprintf('Extraction of pages is not allowed with this credentials (%s).', $secHandler->getAuthMode()),
                        SetaPDF_Core_SecHandler_Exception::NOT_ALLOWED
                    );
                }
            }

            $ident = $this->_currentDocument->getInstanceIdent();
            if (!isset($addedPages[$ident]))
                $addedPages[$ident] = array();

            $pages = $this->_currentDocument->getCatalog()->getPages();
            if ((null === $documentData[1] || SetaPDF_Merger::PAGES_ALL === $documentData[1]) && !isset($touchedPdfs[$ident])) {
                try {
                    $pages->ensureAllPageObjects();
                } catch (BadMethodCallException $e) {
                }
            }

            $pagesToAdd = array();

            $pageCount = $pages->count();

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                if ($this->_checkPageNumber($pageNumber, $documentData[1])) {

                    $page = $pages->extract($pageNumber, $resDocument);
                    $page->flattenInheritedAttributes();

                    $this->_beforePageAdded($page, count($pagesToAdd) + $resPages->count() + 1);

                    /* Info: It is NOT faster to resolve the page object directly
                     * without the wrapper class SetaPDF_Core_Document_Page
                     */
                    $pagesToAdd[] = $page;

                    // rem page object for named destinations
                    if (isset($documentData[2]) && !isset($namedDestinations[$documentData[2]])) {
                        $namedDestinations[$documentData[2]] = ($resPages->count() + count($pagesToAdd));
                    }

                    $resDocument->unBlockReferencedObject($pages->getPagesIndirectObject($pageNumber));
                    $addedPages[$ident][$pageNumber] = true;

                    // Handle outline
                    if (isset($documentData[3]) && !isset($outlineTargets[$currentDocumentId]) &&
                        (is_string($documentData[3]) || (is_array($documentData[3]) && isset($documentData[3][self::OUTLINES_TITLE])))
                    ) {
                        $outlineTargets[$currentDocumentId] = ($resPages->count() + count($pagesToAdd));
                    }

                } elseif (!isset($touchedPdfs[$ident]) && $this->_currentDocument->getInstanceIdent() !== $resDocument->getInstanceIdent()) {
                    // block resolving of not imported pages through references
                    $resDocument->blockReferencedObject($pages->getPagesIndirectObject($pageNumber));
                }
            }

            if (count($pagesToAdd) > 0) {
                $resPages->append($pagesToAdd);
                $touchedPdfs[$ident] = true;

                $resDocument->setMinPdfVersion($this->_currentDocument->getPdfVersion());
            }
        }

        $this->_currentDocument = null;

        if (0 === $resPages->count()) {
            throw new SetaPDF_Merger_Exception(
                'Resulting document has zero pages.'
            );
        }

        if (true === $this->_handleNames || count($namedDestinations) > 0) {
            $this->_handleNames($touchedPdfs, $namedDestinations);
        }

        $this->_handleAcroForms($touchedPdfs);
        $this->_handleOutlines($touchedPdfs, $outlineTargets);
        $this->_handleOptionalContent($touchedPdfs);

        // TODO: cleanUp documents ?!

        $info = $resDocument->getInfo();
        $info->setSyncMetadata(true);
        $date = new SetaPDF_Core_DataStructure_Date();
        $info->setModDate($date);
        if ($info->getCreationDate() === null)
            $info->setCreationDate($date);
        $info->setProducer(
            'SetaPDF-Merger Component v' . self::VERSION .
            ' ©Setasign 2005-' . date('Y') . ' (www.setasign.com)'
        );
        $info->syncMetadata();

        return $resDocument;
    }

    /**
     * Handle creation and import of outlines.
     *
     * @param array $touchedPdfs
     * @param array $outlineTargets
     */
    protected function _handleOutlines($touchedPdfs, $outlineTargets)
    {
        $resDocument = $this->getInitialDocument();
        $resPages = $resDocument->getCatalog()->getPages();
        $outlines = $resDocument->getCatalog()->getOutlines();
        $items = array();

        foreach ($this->_documents AS $currentDocumentId => $documentData) {
            if (!isset($documentData[3]))
                continue;
            $config = $documentData[3];

            // import outlines to root outlines entry
            if (isset($config[self::OUTLINES_COPY]) && $config[self::OUTLINES_COPY] == self::COPY_OUTLINES_TO_ROOT) {
                $currentDocument = $this->_getDocument($documentData[0]);
                $outlines->appendChildCopy($currentDocument->getCatalog()->getOutlines(), $resDocument);
                continue;

            } else if (is_string($config) || (is_array($config)/* && isset($config[self::OUTLINES_TITLE])*/)) {
                if (is_string($config)) {
                    $config = array(self::OUTLINES_TITLE => $config);
                }

                // create outline item
                if (isset($config[self::OUTLINES_PARENT]) &&
                    $config[self::OUTLINES_PARENT] instanceof SetaPDF_Core_Document_OutlinesItem) {
                    $target = $config[self::OUTLINES_PARENT];

                } else {
                    $target = isset($config[self::OUTLINES_PARENT]) && isset($items[$config[self::OUTLINES_PARENT]])
                        ? $items[$config[self::OUTLINES_PARENT]]
                        : $outlines;
                }

                if (isset($config[self::OUTLINES_TITLE])) {
                    $items[$currentDocumentId] = SetaPDF_Core_Document_OutlinesItem::create($resDocument, $config);
                    $items[$currentDocumentId]->setDestination(SetaPDF_Core_Document_Destination::createDestinationArray(
                        $resPages->getPagesIndirectObject($outlineTargets[$currentDocumentId])
                    ));

                    $target->appendChild($items[$currentDocumentId]);
                }

                // import outlines as childs to the newly created outline
                if (isset($config[self::OUTLINES_COPY]) && $config[self::OUTLINES_COPY] == self::COPY_OUTLINES_AS_CHILDS) {
                    $currentDocument = $this->_getDocument($documentData[0]);
                    if (isset($config[self::OUTLINES_TITLE])) {
                        $items[$currentDocumentId]->appendChildCopy($currentDocument->getCatalog()->getOutlines(), $resDocument);
                    } else {
                        $target->appendChildCopy($currentDocument->getCatalog()->getOutlines(), $resDocument);
                    }
                }
            }
        }
    }

    /**
     * Handle AcroForm data.
     *
     * @param array $touchedPdfs
     */
    protected function _handleAcroForms($touchedPdfs)
    {
        if ($this->_renameSameNamedFormFields) {
            $this->_handleAcroFormsByRenamingSameNamedFields($touchedPdfs);
        } else {
            $this->_handleAcroFormsByMergingSameNamedFields($touchedPdfs);
        }
    }

    /**
     * Handles AcroForm data by merging same named form fields.
     *
     * @param array $touchedPdfs
     */
    protected function _handleAcroFormsByMergingSameNamedFields($touchedPdfs)
    {
        /**
         * 1. Resolve all field names of the initial document
         * 2. Walk through all other documents
         * 2a. Copy fields if the names do not exists
         * 2b. If a fieldname exists and the field is not from the same document, append it's terminal fields to the
         *     existing field. By adding a kids entry (if not already existing) prior its terminal field
         */
        $resDocument = $this->getInitialDocument();
        $resAcroForm = $resDocument->getCatalog()->getAcroForm();
        $resFieldsArray = $resAcroForm->getFieldsArray();
        $resAcroFormInitiated = false;

        $names = array();
        $parents = array();

        if ($resFieldsArray && $resFieldsArray->count() > 0) {
            $initialFieldsObjects = $resAcroForm->getTerminalFieldsObjects();
            foreach ($initialFieldsObjects AS $terminalObject) {
                // We need the object holding the "T" entry, because this may be cloned and attached to the resulting document already
                $object = SetaPDF_Core_Type_Dictionary_Helper::resolveObjectByAttribute($terminalObject, 'T');
                $name = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($resDocument->ensureObject($object)->ensure());
                $names[$name] = $terminalObject;

                if ($terminalObject->ensure(true)->offsetExists('Parent')) {
                    $parent = $terminalObject->ensure(true)->getValue('Parent')->getValue();
                    $parentName = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($resDocument->ensureObject($parent)->ensure());
                    $parents[$parentName] = $parent;
                }
            }
        }

        foreach ($this->_documents AS $documentData) {
            $document = $this->_getDocument($documentData[0]);
            $ident = $document->getInstanceIdent();
            if (!isset($touchedPdfs[$ident]))
                continue;

            $acroForm = $document->getCatalog()->getAcroForm();
            $fieldsArray = $acroForm->getFieldsArray();
            if (false === $fieldsArray || $fieldsArray->count() === 0)
                continue;

            // Setup the AcroForm entry in the resulting document
            if (false === $resAcroFormInitiated) {
                $resAcroForm->addDefaultEntriesAndValues();
                $resFieldsArray = $resAcroForm->getFieldsArray();
                $resAcroFormInitiated = true;
            }

            // Copy DR field values
            $resAcroFormDict = $resAcroForm->getDictionary();
            $acroFormDict = $acroForm->getDictionary();
            if ($acroFormDict->offsetExists('DR')) {
                $resDr = $resAcroFormDict->getValue('DR');
                foreach ($acroFormDict->getValue('DR') AS $name => $values) {
                    if (!$resDr->offsetExists($name))
                        $resDr[$name] = clone $values;

                    foreach ($values AS $resName => $value) {
                        $resDict = $resDr[$name]->getValue();
                        if (!$resDict->offsetExists($resName))
                            $resDict->offsetSet($resName, clone $value);
                    }
                }
            }

            $fieldsObjects = $acroForm->getTerminalFieldsObjects();

            foreach ($fieldsObjects AS $object) {
                $dict = $object->ensure(true);
                $name = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($dict);
                // resolve parent name
                if ($dict->offsetExists('Parent')) {
                    $parent = $dict->getValue('Parent')->getValue();
                    $parentName = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($parent->ensure(true));
                } else {
                    $parentName = null;
                }

                // If a same named field already exists
                if (isset($names[$name])) {
                    // cloning this value will not work in all cases
                    $existingDict = $names[$name]->ensure(true);

                    $parentObject = $existingDict->getValue('Parent');

                    if (null === $parentObject || (
                        $existingDict->getValue('T') && $dict->getValue('T') &&
                        SetaPDF_Core_Encoding::convertPdfString($existingDict->getValue('T')->getValue()) ==
                        SetaPDF_Core_Encoding::convertPdfString($dict->getValue('T')->getValue())
                    )) {
                        $names[$name] = $resDocument->createNewObject($names[$name]);

                        // 1. Remove the existing field from the Fields array or the Kids array of the direct parent
                        // 2. Create a new intermediate field, with data from the original field + Kids array
                        // 3. Remove the V, DV and T value from the terminal field and use this field dictionary in the next step
                        // 4. Add the removed field to the new Kids array
                        //
                        $parentFieldsArray = $parentObject ? $parentObject->ensure(true)->getValue('Kids')->ensure(true) : $resFieldsArray;
                        $idx = $parentFieldsArray->indexOf($names[$name]);
                        $parentFieldsArray->offsetUnset($idx);

                        $ft = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($existingDict, 'FT');
                        $intermediate = new SetaPDF_Core_Type_Dictionary(array(
                            clone $existingDict->offsetGet('T'),
                            'V' => $existingDict->offsetExists('V') ? clone $existingDict->getValue('V')->ensure() : new SetaPDF_Core_Type_String(),
                            'FT' => clone $ft,
                            'Kids' => new SetaPDF_Core_Type_Array(array(
                                new SetaPDF_Core_Type_IndirectReference($names[$name])
                            ))
                        ));

                        if ($parentObject) {
                            $intermediate['Parent'] = $parentObject;
                        }

                        if ($existingDict->offsetExists('DV')) {
                            $intermediate['DV'] = clone $existingDict->offsetGet('DV');
                        }

                        $ff = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($existingDict, 'Ff', null);
                        if ($ff !== null) {
                            $intermediate['Ff'] = clone $ff;
                        }

                        unset($existingDict['V'], $existingDict['DV'], $existingDict['T'], $existingDict['FT'], $existingDict['Ff']);

                        $parentObject = $resDocument->createNewObject($intermediate);
                        $parentFieldsArray[] = $parentObject;

                        $existingDict['Parent'] = $parentObject;
                    }

                    $newDict = $object->ensure(true);
                    $newDict['Parent'] = $parentObject;
                    unset($newDict['V'], $newDict['DV'], $newDict['T'], $newDict['FT'], $newDict['Ff']);

                    $parentDict = $parentObject->ensure();

                    // make sure that P is removed if Kids array is available
                    // because it will result in invalid page references
                    //
                    unset($parentDict['P']);

                    $kids = $parentDict['Kids']->ensure();
                    // Add the field only if not already done
                    if ($kids->indexOf($object) === -1)
                        $kids[] = $object;

                    //$names[$name] = $object;

                // A field where a parent one exists
                } elseif (isset($parents[$parentName])) {
                    $parentDict = $parents[$parentName]->ensure(true);
                    $kids = $parentDict['Kids']->ensure();

                    if ($kids->indexOf($object) === -1) {
                        $object->ensure(true)->offsetSet('Parent', $parents[$parentName]);
                        $kids[] = $object;
                    }

                    $names[$name] = $object;

                // A new field
                } else {
                    $terminalObject = $object;
                    // Add the root field to the Fields array
                    while ($dict->offsetExists('Parent')) {
                        $object = $dict->getValue('Parent');
                        $dict = $object->ensure(true);
                    }

                    if ($resFieldsArray->indexOf($object) === -1)
                        $resFieldsArray[] = $object;

                    $names[$name] = $terminalObject;

                    if ($parentName !== null && !isset($parents[$parentName])) {
                        $parents[$parentName] = $parent;
                    }
                }
            }
        }

        $co = $acroForm->getCalculationOrderArray();
        if ($co) {
            $resAcroForm->getCalculationOrderArray(true)->merge($co);
        }
    }

    /**
     * Handles AcroForm data by renaming same named form fields.
     *
     * @param array $touchedPdfs
     */
    protected function _handleAcroFormsByRenamingSameNamedFields($touchedPdfs)
    {
        /* The form field remaining has to be done in this method:
         * 
         * 1. Get the field names document by document
         * 2. Check the current document names against all read fields
         * 3. If a name already exists in another document start a loop which checks a
         *    against all documents and fields names by checking against the value instead of the key 
         */

        $resDocument = $this->getInitialDocument();
        $resAcroForm = $resDocument->getCatalog()->getAcroForm();
        $resFieldsArray = $resAcroForm->getFieldsArray();
        $resAcroFormInitiated = false;

        $resIdent = $resDocument->getInstanceIdent();
        $names = array($resIdent => array());

        if ($resFieldsArray && $resFieldsArray->count() > 0) {
            $initialFieldsObjects = $resAcroForm->getTerminalFieldsObjects();
            foreach ($initialFieldsObjects AS $object) {
                // We need the object holding the "T" entry, because this may be cloned and attached to the resulting document already
                $object = SetaPDF_Core_Type_Dictionary_Helper::resolveObjectByAttribute($object, 'T');
                $name = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($resDocument->ensureObject($object)->ensure());
                $names[$resIdent][$name] = $name;
            }
        }

        $renamed = array();

        foreach ($this->_documents AS $documentData) {
            $document = $this->_getDocument($documentData[0]);
            $ident = $document->getInstanceIdent();
            if (!isset($touchedPdfs[$ident]))
                continue;

            $acroForm = $document->getCatalog()->getAcroForm();
            $fieldsArray = $acroForm->getFieldsArray();
            if (false === $fieldsArray || $fieldsArray->count() === 0)
                continue;

            $fieldsArray = $fieldsArray->deepClone($resDocument);

            // Setup the AcroForm entry in the resulting document
            if (false === $resAcroFormInitiated) {
                $resAcroForm->addDefaultEntriesAndValues();
                $resFieldsArray = $resAcroForm->getFieldsArray();
                $resAcroFormInitiated = true;
            }

            $fieldsObjects = $acroForm->getTerminalFieldsObjects();

            foreach ($fieldsObjects AS $object) {
                $object = $object->deepClone($resDocument);
                $dict = $object->ensure(true);
                $name = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($dict);
                $names[$ident][$name] = $name;

                if (isset($renamed[$ident][$name])) {
                    $a = $renamed[$ident][$name];
                } else {
                    $a = -1;
                    $newName = $name;
                    reset($names);
                    while (($_names = current($names)) !== false) {
                        $_ident = key($names);

                        // do not check same document
                        if ($_ident === $ident) {
                            next($names);
                            continue;
                        }

                        if (in_array($newName, $_names)) {
                            $a++;
                            $newName = $name . '-' . $a;
                            reset($names);
                            continue;
                        }

                        next($names);
                    }
                }

                if ($a === -1) {
                    continue;
                }

                $suffix = $a;
                $renamed[$ident][$name] = $suffix;
                $names[$ident][$name] = $newName;

                $object = SetaPDF_Core_Type_Dictionary_Helper::resolveObjectByAttribute($object, 'T');
                $t = $object->ensure(true)->getValue('T');
                $name = $t->getValue();

                if (strpos($name, "\xFE\xFF") === 0) {
                    $name .= SetaPDF_Core_Encoding::convert('-' . $suffix, 'UTF-8', 'UTF-16BE');
                } else {
                    $name .= '-' . $suffix;
                }

                $t->setValue($name);
            }

            $this->rewrittenFormFieldNamesData = $names;

            $resFieldsArray->mergeUnique($fieldsArray);

            $co = $acroForm->getCalculationOrderArray();
            if ($co) {
                $resAcroForm->getCalculationOrderArray(true)->merge($co);
            }
        }
    }

    /**
     * Imports names of all used documents and defined named destinations.
     *
     * @param array $touchedPdfs
     * @param array $namedDestinations
     */
    protected function _handleNames($touchedPdfs, $namedDestinations)
    {
        $resDocument = $this->getInitialDocument();
        $resPages = $resDocument->getCatalog()->getPages();

        $resNames = $resDocument->getCatalog()->getNames();
        if (count($namedDestinations) > 0) {
            $dests = $resNames->getTree(SetaPDF_Core_Document_Catalog_Names::DESTS, true);
            foreach ($namedDestinations AS $name => $pageNumber) {
                $destArray = SetaPDF_Core_Document_Destination::createDestinationArray(
                    $resPages->getPagesIndirectObject($pageNumber)
                );
                $dests->add($name, $destArray);
            }
        }

        if (true === $this->_handleNames) {
            $this->_renamed = array();
            $resultIdent = $resDocument->getInstanceIdent();
            $namesCopied = array();
            foreach ($this->_documents AS $documentData) {
                $document = $this->_getDocument($documentData[0]);
                $ident = $document->getInstanceIdent();
                if (!isset($touchedPdfs[$ident]) || $resultIdent === $ident || isset($namesCopied[$ident]))
                    continue;

                $this->_renamed[$ident] = array();

                $names = $document->getCatalog()->getNames();
                $trees = $names->getTrees();
                foreach ($trees AS $name => $tree) {
                    $resTree = $resNames->getTree($name, true);
                    $_renamed = $resTree->merge($tree, $this->_adjustNameCallback);
                    $this->_renamed[$ident] = array_merge($this->_renamed[$ident], $_renamed);
                    $namesCopied[$ident] = true;
                }

                if (count($this->_renamed[$ident])) {
                    $document->registerWriteCallback(
                        array($this, 'rewriteNamesCallback'),
                        'SetaPDF_Core_Type_String',
                        'rewrite strings'
                    );

                    $document->registerWriteCallback(
                        array($this, 'rewriteNamesCallback'),
                        'SetaPDF_Core_Type_HexString',
                        'rewrite hex strings'
                    );
                }
            }
        }
    }

    /**
     * Handles optional content data (Layers).
     *
     * @param array $touchedPdfs
     */
    protected function _handleOptionalContent(array $touchedPdfs)
    {
        $processedPdfs = array();

        $optionalContent = $this->getDocument()->getCatalog()->getOptionalContent();

        foreach ($this->_documents AS $documentData) {
            $document = $this->_getDocument($documentData[0]);
            $ident = $document->getInstanceIdent();
            if ($documentData[4] !== true || !isset($touchedPdfs[$ident]) || isset($processedPdfs[$ident]))
                continue;

            $_optionalContent = $document->getCatalog()->getOptionalContent();

            $orderArray = $_optionalContent->getOrderArray();
            if ($orderArray) {
                $optionalContent->getOrderArray(true)->merge($orderArray);
            }

            $onArray = $_optionalContent->getOnArray();
            if ($onArray) {
                $optionalContent->getOnArray(true)->merge($onArray);
            }

            $offArray = $_optionalContent->getOffArray();
            if ($offArray) {
                $optionalContent->getOffArray(true)->merge($offArray);
            }

            $asArray = $_optionalContent->getAsArray();
            if ($asArray) {
                $optionalContent->getAsArray(true)->merge($asArray);
            }

            foreach ($_optionalContent->getGroups() AS $group) {
                if ($group instanceof SetaPDF_Core_Document_OptionalContent_Group) {
                    $optionalContent->addGroup($group);
                }
            }

            $processedPdfs[$ident] = true;
        }
    }

    /**
     * Callback method for renaming string values of renamed names.
     *
     * @see SetaPDF_Merger::_handleNames()
     * @param SetaPDF_Core_Document $document The document instance
     * @param SetaPDF_Core_Type_StringValue $value The string value
     */
    public function rewriteNamesCallback(SetaPDF_Core_Document $document, SetaPDF_Core_Type_StringValue $value)
    {
        $ident = $document->getInstanceIdent();
        if (0 === count($this->_renamed[$ident])) {
            return;
        }

        $currentValue = $value->getValue();
        if (isset($this->_renamed[$ident][$currentValue])) {
            $value->setValue($this->_renamed[$ident][$currentValue]);
        }
    }

    /**
     * Checks a page number against a condition.
     *
     * @param integer $pageNumber The page number
     * @param null|integer|string|array|callback $condition Valid conditions are:
     *          <ul>
     *          <li><b>PAGES_XXX</b> constant or <b>null</b> (equal to {@link SetaPDF_Merger::PAGES_ALL})</li>
     *          <li><b>Integer</b> with the valid page number</li>
     *          <li><b>String</b> with the valid page number or the valid range (e.g. '10-12')</li>
     *          <li><b>Array</b> with all valid page numbers</li>
     *          <li><b>Callback</b> with the arguments (int $pageNumber, SetaPDF_Core_Document $document)</li>
     *          </ul>
     * @return boolean
     */
    protected function _checkPageNumber($pageNumber, $condition = null)
    {
        if (
            null === $condition ||
            $condition === self::PAGES_ALL ||
            $pageNumber === 1 && $condition === self::PAGES_FIRST ||
            $condition === self::PAGES_LAST && $this->_currentDocument->getCatalog()->getPages()->count() === $pageNumber
        ) {
            return true;
        }

        if (is_string($condition) && preg_match('~^(\d+)-(\d*)$~', $condition, $matches)) {
            $start = (int)$matches[1];
            $end   = $matches[2] ? (int)$matches[2] : $this->_currentDocument->getCatalog()->getPages()->count();

            return $pageNumber >= $start && $pageNumber <= $end;
        }

        if (is_callable($condition)) {
            return call_user_func_array(
                $condition, array($pageNumber, $this->_currentDocument)
            );
        }

        if (is_array($condition)) {
            return in_array($pageNumber, $condition, true);
        }

        return $pageNumber == $condition;
    }

    /**
     * Get a document instance by filename.
     *
     * @param string|SetaPDF_Core_Document $filename The filename
     * @param boolean $cache Cache the document by filename
     * @return SetaPDF_Core_Document
     */
    protected function _getDocument($filename, $cache = true)
    {
        if ($filename instanceof SetaPDF_Core_Document)
            return $filename;

        if (!isset($this->_documentCache[$filename])) {
            $document = SetaPDF_Core_Document::loadByFilename($filename);
            if ($cache) {
                $this->_documentCache[$filename] = $document;
            } else {
                return $document;
            }
        }

        return $this->_documentCache[$filename];
    }

    /**
     * Get a document instance by a filename.
     *
     * @param string $filename The filename
     * @param boolean $cache Cache the document by filename
     * @return SetaPDF_Core_Document
     */
    public function getDocumentByFilename($filename, $cache = true)
    {
        return $this->_getDocument($filename, $cache);
    }

    /**
     * Get the currently processed document instance.
     *
     * This method can be used to get the document instance that is actually processed if an Exception is thrown.
     *
     * @return SetaPDF_Core_Document
     */
    public function getCurrentDocument()
    {
        return $this->_currentDocument;
    }

    /**
     * Release objects to free memory and cycled references.
     *
     * After calling this method the instance of this object is unusable!
     *
     * @return void
     */
    public function cleanUp()
    {
        foreach (array_keys($this->_documents) AS $key) {
            $document = $this->_getDocument($this->_documents[$key][0]);
            $document->cleanUp();
            unset($this->_documents[$key]);
        }

        $this->_documents = array();

        foreach (array_keys($this->_documentCache) AS $key) {
            $this->_documentCache[$key]->cleanUp();
            unset($this->_documentCache[$key]);
        }

        if (null !== $this->_initialDocument) {
            $this->_initialDocument->cleanUp();
            $this->_initialDocument = null;
        }
    }
}