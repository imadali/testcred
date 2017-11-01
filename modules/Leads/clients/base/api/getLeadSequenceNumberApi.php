<?php
class getLeadSequenceNumberApi extends SugarApi
{
    // This function is only called whenever the rest service cache file is deleted.
    // This shoud return an array of arrays that define how different paths map to different functions
    public function registerApiRest() {
        return array(
            'LeadSequenceNumbers' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'get_lead_sequence_number'),
                'pathVars' => array('', ''),
                'method' => 'LeadSequenceNumbers',
                'shortHelp' => 'Lists at risk accounts in the system',
                'longHelp' => '',
            ),
        );
    }
    
    function LeadSequenceNumbers($api, $args)
    {
        // Start off with something simple so we can verify the endpoint is registered.
        // return 'burgers';
		global $db;
		
        //Gets the highest number of the table for each field
        $query = "SELECT MAX(reference_number_c) as ref_max, max(credit_request_number_c) as cr_max FROM leads LEFT JOIN leads_cstm ON leads.id=leads_cstm.id_c WHERE NOT leads.deleted";
        $rs = $db->query($query);
        $row = $db->fetchByAssoc($rs);
        
        return array(
			'reference_number'       => $row['ref_max'] + 1,
			'credit_request_number'  => $row['cr_max'] + 1 
		);
    }
}

?>