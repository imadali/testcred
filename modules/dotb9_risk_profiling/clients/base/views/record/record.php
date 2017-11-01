<?php
// created: 2017-07-10 14:11:09
$viewdefs['dotb9_risk_profiling']['base']['view']['record'] = array (
  'panels' => 
  array (
    0 => 
    array (
      'name' => 'panel_header',
      'label' => 'LBL_RECORD_HEADER',
      'header' => true,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'picture',
          'type' => 'avatar',
          'width' => 42,
          'height' => 42,
          'dismiss_label' => true,
          'readonly' => true,
        ),
        1 => 'name',
        2 => 
        array (
          'name' => 'favorite',
          'label' => 'LBL_FAVORITE',
          'type' => 'favorite',
          'readonly' => true,
          'dismiss_label' => true,
        ),
        3 => 
        array (
          'name' => 'follow',
          'label' => 'LBL_FOLLOW',
          'type' => 'follow',
          'readonly' => true,
          'dismiss_label' => true,
        ),
      ),
    ),
    1 => 
    array (
      'name' => 'panel_body',
      'label' => 'LBL_RECORD_BODY',
      'columns' => 2,
      'labelsOnTop' => true,
      'placeholders' => true,
      'newTab' => true,
      'panelDefault' => 'expanded',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'accounts_dotb9_risk_profiling_1_name',
          'span' => 12,
        ),
        1 => 
        array (
          'name' => 'more_than_80000_c',
          'studio' => 'visible',
          'label' => 'LBL_MORE_THAN_80000',
        ),
        2 => 
        array (
          'name' => 'postcode_if_liechtenstein_c',
          'studio' => 'visible',
          'label' => 'LBL_POSTCODE_IF_LIECHTENSTEIN',
        ),
        3 => 
        array (
          'name' => 'zip_liechtenstein_and_swiss_c',
          'studio' => 'visible',
          'label' => 'LBL_ZIP_LIECHTENSTEIN_AND_SWISS',
        ),
        4 => 
        array (
          'name' => 'pay_bills_taxes_inv_real_est_c',
          'studio' => 'visible',
          'label' => 'LBL_PAY_BILLS_TAXES_INV_REAL_EST',
        ),
        5 => 
        array (
          'name' => 'currently_open_enforcements_c',
          'studio' => 'visible',
          'label' => 'LBL_CURRENTLY_OPEN_ENFORCEMENTS',
        ),
        6 => 
        array (
          'name' => 'if_enforcements_in_the_past_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_ENFORCEMENTS_IN_THE_PAST',
        ),
      ),
    ),
    2 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL1',
      'label' => 'LBL_RECORDVIEW_PANEL1',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_younger_than_18_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNGER_THAN_18',
        ),
        1 => 
        array (
          'name' => 'if_younger_than_21_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNGER_THAN_21',
        ),
        2 => 
        array (
          'name' => 'if_young_21_credit_amount_15_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNG_21_CREDIT_AMOUNT_15',
        ),
        3 => 
        array (
          'name' => 'if_younger_than_25_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNGER_THAN_25',
        ),
        4 => 
        array (
          'name' => 'if_young_25_credit_amount_25_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNG_25_CREDIT_AMOUNT_25',
        ),
        5 => 
        array (
          'name' => 'if_older_than_59_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_OLDER_THAN_59',
        ),
        6 => 
        array (
          'name' => 'if_older_59_credit_amount_50_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_OLDER_59_CREDIT_AMOUNT_50',
        ),
        7 => 
        array (
          'name' => 'if_younger_than_64_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNGER_THAN_64',
        ),
        8 => 
        array (
          'name' => 'if_younger_64_credit_50000_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YOUNGER_64_CREDIT_50000',
        ),
        9 => 
        array (
          'name' => 'if_older_than_65_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_OLDER_THAN_65',
        ),
        10 => 
        array (
          'name' => 'if_older_than_70_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_OLDER_THAN_70',
        ),
        11 => 
        array (
        ),
      ),
    ),
    3 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL2',
      'label' => 'LBL_RECORDVIEW_PANEL2',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_iran_red_syr_mianmar_sud_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_IRAN_RED_SYR_MIANMAR_SUD',
        ),
        1 => 
        array (
          'name' => 'if_iraq_zim_con_leb_yem_usa_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_IRAQ_ZIM_CON_LEB_YEM_USA',
        ),
        2 => 
        array (
          'name' => 'if_redt_ger_aus_den_sweden_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_REDT_GER_AUS_DEN_SWEDEN',
        ),
        3 => 
        array (
        ),
      ),
    ),
    4 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL3',
      'label' => 'LBL_RECORDVIEW_PANEL3',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_b_permit_less_6_month_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_PERMIT_LESS_6_MONTH',
        ),
        1 => 
        array (
          'name' => 'if_b_permit_btwn_6_12_month_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_PERMIT_BTWN_6_12_MONTH',
        ),
        2 => 
        array (
          'name' => 'if_b_btw_12_net_m_sal_l_4000_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_12_NET_M_SAL_L_4000',
        ),
        3 => 
        array (
          'name' => 'if_b_6_12_sal_btw_4_6_amt_15_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_6_12_SAL_BTW_4_6_AMT_15',
        ),
        4 => 
        array (
          'name' => 'if_b_6_12_sal_btw_6_8_amt_20_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_6_12_SAL_BTW_6_8_AMT_20',
        ),
        5 => 
        array (
          'name' => 'if_b_btw_6_12_m_sal_8_amt_30_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_6_12_M_SAL_8_AMT_30',
        ),
        6 => 
        array (
          'name' => 'if_b_btw_12_24__sal_4_amt_15_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_12_24__SAL_4_AMT_15',
        ),
        7 => 
        array (
          'name' => 'if_b_btw_12_24_sal_4_6_am_25_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_12_24_SAL_4_6_AM_25',
        ),
        8 => 
        array (
          'name' => 'if_b_btw_12_24_sal_6_8_am_30_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_12_24_SAL_6_8_AM_30',
        ),
        9 => 
        array (
          'name' => 'if_b_btw_12_24_sal_8_amnt_40_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_12_24_SAL_8_AMNT_40',
        ),
        10 => 
        array (
          'name' => 'if_b_btw_24_36_sal_4_a_g_25_c',
          'label' => 'LBL_IF_B_BTW_24_36_SAL_4_A_G_25',
        ),
        11 => 
        array (
          'name' => 'if_b_btw_24_36_sal_4_6_am_35_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_24_36_SAL_4_6_AM_35',
        ),
        12 => 
        array (
          'name' => 'if_b_btw_24_36_sal_4_amnt_25_c',
          'label' => 'LBL_IF_B_BTW_24_36_SAL_4_AMNT_25',
        ),
        13 => 
        array (
          'name' => 'if_b_btw_24_36_sal_6_8_am_40_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_24_36_SAL_6_8_AM_40',
        ),
        14 => 
        array (
          'name' => 'if_b_btw_24_36_sal_8_amnt_50_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_BTW_24_36_SAL_8_AMNT_50',
        ),
        15 => 
        array (
          'name' => 'if_b_36_sal_4_credit_amnt_35_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_36_SAL_4_CREDIT_AMNT_35',
        ),
        16 => 
        array (
          'name' => 'if_b_36_sal_4_6_credit_am_45_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_36_SAL_4_6_CREDIT_AM_45',
        ),
        17 => 
        array (
          'name' => 'if_b_36_sal_6_8_credit_am_50_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_36_SAL_6_8_CREDIT_AM_50',
        ),
        18 => 
        array (
          'name' => 'if_b_36_sal_8_credit_amnt_60_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_36_SAL_8_CREDIT_AMNT_60',
        ),
        19 => 
        array (
          'name' => 'if_b_permit_btw_12_24_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_PERMIT_BTW_12_24_MONTHS',
        ),
        20 => 
        array (
          'name' => 'if_b_permit_btw_24_36_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_PERMIT_BTW_24_36_MONTHS',
        ),
        21 => 
        array (
          'name' => 'if_b_12_alo_child_sin_parent_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_B_12_ALO_CHILD_SIN_PARENT',
        ),
        22 => 
        array (
          'name' => 'if_g_less_3_years_employer_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_G_LESS_3_YEARS_EMPLOYER',
        ),
        23 => 
        array (
          'name' => 'if_g_permit_more_3_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_G_PERMIT_MORE_3_YEARS',
        ),
        24 => 
        array (
          'name' => 'if_l_permit_less_1_year_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_L_PERMIT_LESS_1_YEAR',
        ),
        25 => 
        array (
          'name' => 'if_l_permit_more_than_1_year_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_L_PERMIT_MORE_THAN_1_YEAR',
        ),
        26 => 
        array (
          'name' => 'if_diplomat_less_3_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_DIPLOMAT_LESS_3_YEARS',
        ),
        27 => 
        array (
          'name' => 'if_diplomat_more_3_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_DIPLOMAT_MORE_3_YEARS',
        ),
        28 => 
        array (
          'span' => 12,
        ),
      ),
    ),
    5 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL4',
      'label' => 'LBL_RECORDVIEW_PANEL4',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_self_emp_less_2_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_SELF_EMP_LESS_2_YEARS',
        ),
        1 => 
        array (
          'name' => 'if_self_more_2_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_SELF_MORE_2_YEARS',
        ),
        2 => 
        array (
          'name' => 'if_unemp_not_working_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_UNEMP_NOT_WORKING',
        ),
        3 => 
        array (
          'name' => 'if_temp_cont_6_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_TEMP_CONT_6_MONTHS',
        ),
        4 => 
        array (
          'name' => 'if_temp_cont_6_12_mon_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_TEMP_CONT_6_12_MON',
        ),
        5 => 
        array (
          'name' => 'if_temp_cont_12_month_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_TEMP_CONT_12_MONTH',
        ),
        6 => 
        array (
          'name' => 'if_disable_pension_ret_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_DISABLE_PENSION_RET',
        ),
        7 => 
        array (
        ),
      ),
    ),
    6 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL5',
      'label' => 'LBL_RECORDVIEW_PANEL5',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_less_than_3_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_LESS_THAN_3_MONTHS',
        ),
        1 => 
        array (
          'name' => 'if_less_than_12_month_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_LESS_THAN_12_MONTH',
        ),
      ),
    ),
    7 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL6',
      'label' => 'LBL_RECORDVIEW_PANEL6',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_with_par_by_parent_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_WITH_PAR_BY_PARENT',
        ),
        1 => 
        array (
          'name' => 'if_urbanize_flat_share_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_URBANIZE_FLAT_SHARE',
        ),
      ),
    ),
    8 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL7',
      'label' => 'LBL_RECORDVIEW_PANEL7',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_less_than_12_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_LESS_THAN_12_MONTHS',
        ),
        1 => 
        array (
          'name' => 'if_less_than_24_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_LESS_THAN_24_MONTHS',
        ),
      ),
    ),
    9 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL8',
      'label' => 'LBL_RECORDVIEW_PANEL8',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_no_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_NO',
        ),
        1 => 
        array (
          'name' => 'if_yes_and_less_than_2_years_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YES_AND_LESS_THAN_2_YEARS',
        ),
        2 => 
        array (
          'name' => 'if_yes_long_2_current_adress_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YES_LONG_2_CURRENT_ADRESS',
        ),
        3 => 
        array (
        ),
      ),
    ),
    10 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL9',
      'label' => 'LBL_RECORDVIEW_PANEL9',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_yes_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_YES',
        ),
        1 => 
        array (
        ),
      ),
    ),
    11 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL10',
      'label' => 'LBL_RECORDVIEW_PANEL10',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_customer_receives_alimony_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_CUSTOMER_RECEIVES_ALIMONY',
        ),
        1 => 
        array (
          'name' => 'if_customer_has_pay_alimony_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_CUSTOMER_HAS_PAY_ALIMONY',
        ),
      ),
    ),
    12 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL11',
      'label' => 'LBL_RECORDVIEW_PANEL11',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_div_judicial_sep_div_sep_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_DIV_JUDICIAL_SEP_DIV_SEP',
        ),
        1 => 
        array (
          'name' => 'if_married_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_MARRIED',
        ),
      ),
    ),
    13 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL12',
      'label' => 'LBL_RECORDVIEW_PANEL12',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'no_code_and_cannot_be_found_c',
          'studio' => 'visible',
          'label' => 'LBL_NO_CODE_AND_CANNOT_BE_FOUND',
        ),
        1 => 
        array (
          'name' => 'code_1_c',
          'studio' => 'visible',
          'label' => 'LBL_CODE_1',
        ),
        2 => 
        array (
          'name' => 'code_2_c',
          'studio' => 'visible',
          'label' => 'LBL_CODE_2',
        ),
        3 => 
        array (
          'name' => 'code_3_c',
          'studio' => 'visible',
          'label' => 'LBL_CODE_3',
        ),
        4 => 
        array (
          'name' => 'code_4_c',
          'studio' => 'visible',
          'label' => 'LBL_CODE_4',
        ),
        5 => 
        array (
          'name' => 'if_dv_score_440_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_DV_SCORE_440',
        ),
      ),
    ),
    14 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL13',
      'label' => 'LBL_RECORDVIEW_PANEL13',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'total_income_less_than_2500_c',
          'studio' => 'visible',
          'label' => 'LBL_TOTAL_INCOME_LESS_THAN_2500',
        ),
        1 => 
        array (
          'name' => 'total_income_less_than_3000_c',
          'studio' => 'visible',
          'label' => 'LBL_TOTAL_INCOME_LESS_THAN_3000',
        ),
        2 => 
        array (
          'name' => 'total_income_less_than_4000_c',
          'studio' => 'visible',
          'label' => 'LBL_TOTAL_INCOME_LESS_THAN_4000',
        ),
        3 => 
        array (
        ),
      ),
    ),
    15 => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
      'name' => 'LBL_RECORDVIEW_PANEL14',
      'label' => 'LBL_RECORDVIEW_PANEL14',
      'columns' => 2,
      'labelsOnTop' => 1,
      'placeholders' => 1,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'if_credit_amount_80000_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_CREDIT_AMOUNT_80000',
        ),
        1 => 
        array (
          'name' => 'if_credit_duration_60_months_c',
          'studio' => 'visible',
          'label' => 'LBL_IF_CREDIT_DURATION_60_MONTHS',
        ),
      ),
    ),
    16 => 
    array (
      'name' => 'panel_hidden',
      'label' => 'LBL_SHOW_MORE',
      'hide' => true,
      'columns' => 2,
      'labelsOnTop' => true,
      'placeholders' => true,
      'newTab' => true,
      'panelDefault' => 'expanded',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'description',
          'span' => 12,
        ),
        1 => 
        array (
          'name' => 'assigned_user_name',
        ),
        2 => 
        array (
          'name' => 'team_name',
        ),
        3 => 
        array (
          'name' => 'has_intrum_response_c',
          'label' => 'LBL_HAS_INTRUM_RESPONSE',
        ),
        4 => 
        array (
          'name' => 'has_deltavista_response_c',
          'label' => 'LBL_HAS_DELTAVISTA_RESPONSE',
        ),
        5 => 
        array (
          'name' => 'intrum_request_id_c',
          'label' => 'LBL_INTRUM_REQUEST_ID',
        ),
        6 => 
        array (
          'name' => 'deltavista_request_id_c',
          'label' => 'LBL_DELTAVISTA_REQUEST_ID',
        ),
        7 => 
        array (
          'name' => 'intrum_score_c',
          'label' => 'LBL_INTRUM_SCORE',
        ),
        8 => 
        array (
          'name' => 'deltavista_score_c',
          'label' => 'LBL_DELTAVISTA_SCORE',
        ),
        9 => 
        array (
          'name' => 'date_modified_by',
          'readonly' => true,
          'inline' => true,
          'type' => 'fieldset',
          'label' => 'LBL_DATE_MODIFIED',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'date_modified',
            ),
            1 => 
            array (
              'type' => 'label',
              'default_value' => 'LBL_BY',
            ),
            2 => 
            array (
              'name' => 'modified_by_name',
            ),
          ),
        ),
        10 => 
        array (
          'name' => 'date_entered_by',
          'readonly' => true,
          'inline' => true,
          'type' => 'fieldset',
          'label' => 'LBL_DATE_ENTERED',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'date_entered',
            ),
            1 => 
            array (
              'type' => 'label',
              'default_value' => 'LBL_BY',
            ),
            2 => 
            array (
              'name' => 'created_by_name',
            ),
          ),
        ),
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'useTabs' => true,
  ),
  'buttons' => 
  array (
    0 => 
    array (
      'type' => 'button',
      'name' => 'cancel_button',
      'label' => 'LBL_CANCEL_BUTTON_LABEL',
      'css_class' => 'btn-invisible btn-link',
      'showOn' => 'edit',
      'events' => 
      array (
        'click' => 'button:cancel_button:click',
      ),
    ),
    1 => 
    array (
      'type' => 'rowaction',
      'event' => 'button:save_button:click',
      'name' => 'save_button',
      'label' => 'LBL_SAVE_BUTTON_LABEL',
      'css_class' => 'btn btn-primary',
      'showOn' => 'edit',
      'acl_action' => 'edit',
    ),
    2 => 
    array (
      'type' => 'actiondropdown',
      'name' => 'main_dropdown',
      'primary' => true,
      'showOn' => 'view',
      'buttons' => 
      array (
        0 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:edit_button:click',
          'name' => 'edit_button',
          'label' => 'LBL_EDIT_BUTTON_LABEL',
          'acl_action' => 'edit',
        ),
        1 => 
        array (
          'type' => 'shareaction',
          'name' => 'share',
          'label' => 'LBL_RECORD_SHARE_BUTTON',
          'acl_action' => 'view',
        ),
        2 => 
        array (
          'type' => 'pdfaction',
          'name' => 'download-pdf',
          'label' => 'LBL_PDF_VIEW',
          'action' => 'download',
          'acl_action' => 'view',
        ),
        3 => 
        array (
          'type' => 'pdfaction',
          'name' => 'email-pdf',
          'label' => 'LBL_PDF_EMAIL',
          'action' => 'email',
          'acl_action' => 'view',
        ),
        4 => 
        array (
          'type' => 'divider',
        ),
        5 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:find_duplicates_button:click',
          'name' => 'find_duplicates_button',
          'label' => 'LBL_DUP_MERGE',
          'acl_action' => 'edit',
        ),
        6 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:duplicate_button:click',
          'name' => 'duplicate_button',
          'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
          'acl_module' => 'dotb9_risk_profiling',
          'acl_action' => 'create',
        ),
        7 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:audit_button:click',
          'name' => 'audit_button',
          'label' => 'LNK_VIEW_CHANGE_LOG',
          'acl_action' => 'view',
        ),
        8 => 
        array (
          'type' => 'divider',
        ),
        9 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:delete_button:click',
          'name' => 'delete_button',
          'label' => 'LBL_DELETE_BUTTON_LABEL',
          'acl_action' => 'delete',
        ),
      ),
    ),
    3 => 
    array (
      'name' => 'sidebar_toggle',
      'type' => 'sidebartoggle',
    ),
  ),
);