<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php

	global $g_tld;

	$g_tld = array('ac'=>'United Kingdom Academic Institutions','ad'=>'Andorra','ae'=>'United Arab Emirates','af'=>'Afghanistan','ag'=>'Antigua and Barbuda','ai'=>'Anguilla','al'=>'Albania','am'=>'Armenia','an'=>'Netherlands Antilles','ao'=>'Angola','aq'=>'Antarctica','ar'=>'Argentina','as'=>'American Samoa','at'=>'Austria','au'=>'Australia','aw'=>'Aruba','az'=>'Azerbaijan','ba'=>'Bosnia and Herzegovina','bb'=>'Barbados','bd'=>'Bangladesh','be'=>'Belgium','bf'=>'Burkina Faso','bg'=>'Bulgaria','bh'=>'Bahrain','bi'=>'Burundi','bj'=>'Benin','bm'=>'Bermuda ','bn'=>'Brunei Darussalam','bo'=>'Bolivia','br'=>'Brazil','bs'=>'Bahamas','bt'=>'Bhutan','bv'=>'Bouvet Island','bw'=>'Botswana','by'=>'Belarus','bz'=>'Belize','ca'=>'Canada','cc'=>'Cocos (Keeling) Islands','cf'=>'Central African Republic','cg'=>'Congo','ch'=>'Switzerland','ci'=>'Cote d`Ivoire (Ivory Coast)','ck'=>'Cook Islands','cl'=>'Chile','cm'=>'Cameroon','cn'=>'China','co'=>'Colombia','com'=>'US Commercial','cr'=>'Costa Rica','cs'=>'Czechoslovakia (former)','cu'=>'Cuba','cv'=>'Cape Verde','cx'=>'Christmas Island','cy'=>'Cyprus','cz'=>'Czech Republic','de'=>'Germany','dj'=>'Djibouti','dk'=>'Denmark','dm'=>'Dominica','do'=>'Dominican Republic','dz'=>'Algeria','ec'=>'Ecuador','edu'=>'US Educational','ee'=>'Estonia','eg'=>'Egypt','eh'=>'Western Sahara','er'=>'Eritrea','es'=>'Spain','et'=>'Ethiopia','fi'=>'Finland','fj'=>'Fiji','fk'=>'Falkland Islands (Malvinas)','fm'=>'Micronesia','fo'=>'Faroe Islands','fr'=>'France','fx'=>'France (Metropolitan)','ga'=>'Gabon','gb'=>'Great Britain (UK)','gd'=>'Grenada','ge'=>'Georgia','gf' =>'French Guiana','gh'=>'Ghana','gi'=>'Gibraltar','gl'=>'Greenland','gm'=>'Gambia','gn'=>'Guinea','gov'=>'US Government','gp'=>'Guadaloupe','gq'=>'Equatorial Guinea','gr'=>'Greece','gs'=>'South Georgia and South Sandwich Islands','gt'=>'Guatemala','gu'=>'Guam','gw'=>'Guinea-Bissau','gy'=>'Guyana','hk'=>'Hong Kong','hm'=>'Heard and McDonald Islands','hn'=>'Honduras','hr'=>'Croatia (Hrvatska)','ht'=>'Haiti','hu'=>'Hungary','ic'=>'Iceland','id'=>'Indonesia','ie'=>'Ireland','il'=>'Israel','in'=>'India','io'=>'British Indian Ocean Territory','ip'=>'IP Address','iq'=>'Iraq','ir'=>'Iran','it'=>'Italy','jm'=>'Jamaica','jo'=>'Jordan','jp'=>'Japan','ke'=>'Kenya','kg'=>'Kyrgyzstan','kh'=>'Cambodia','ki'=>'Kiribati','km'=>'Comoros','kn'=>'Saint Kitts and Nevis','kp'=>'Korea (North)','kr'=>'Korea (South)','ku'=>'Kuwait','ky'=>'Cayman Islands','kz'=>'Kazakhstan','la'=>'Laos','lb'=>'Lebanon','lc'=>'Saint Lucia','li'=>'Liechtenstein','lk'=>'Sri Lanka','lr'=>'Liberia','ls'=>'Lesotho','lt'=>'Lithuania','lu'=>'Luxembourg','lv'=>'Latvia','ly'=>'Libya','ma'=>'Morocco','mc'=>'Monaco','md'=>'Moldova','mg'=>'Madagascar','mh'=>'Marshall Islands','mil'=>'US Military','mk'=>'Macedonia','ml'=>'Mali','mm'=>'Mynamar','mn'=>'Mongolia','mo'=>'Macau','mp'=>'Northern Mariana Islands','mq'=>'Martinique','mr'=>'Mauritania','ms'=>'Montserrat','mt'=>'Malta','mu'=>'Mauritius','mv'=>'Maldives','mw'=>'Malawi','mx'=>'Mexico','my'=>'Malaysia','mz'=>'Mozambique','na'=>'Namibia','nc'=>'New Caledonia','ne'=>'Niger','net'=>'US Network','nf'=>'Norfolk Island','ng'=>'Nigeria','ni'=>'Nicaragua','nl'=>'Netherlands','no'=>'Norway','np'=>'Nepal','nr'=>'Nauru','nt'=>'Neutral Zone','nu'=>'Niue','nz'=>'New Zealand (Aotearoa)','om'=>'Oman','org'=>'US Non-Profit Organization','pa'=>'Panama','pe'=>'Peru','pf'=>'French Polynesia','pg'=>'Papua New Guinea','ph'=>'Philippines','pk'=>'Pakistan','pl'=>'Poland','pm'=>'Saint Pierre and Miquelon','pn'=>'Pitcairn','pr'=>'Puerto Rico','pt'=>'Portugal','pw'=>'Palau','py'=>'Paraguay','qa'=>'Qatar','re'=>'Reunion','ro'=>'Romania','ru'=>'Russian Federation','rw'=>'Rwanda','sa'=>'Saudi Arabia','sb'=>'Solomon Islands','sc'=>'Seychelles','sd'=>'Sudan','se'=>'Sweden','sg'=>'Singapore','sh'=>'Saint Helena','si'=>'Slovenia','sj'=>'Svalbard and Jan Mayen Islands','sk'=>'Slovak Republic','sl'=>'Sierra Leone','sm'=>'San Marino','sn'=>'Senegal','so'=>'Somalia','sr'=>'Suriname','st'=>'Sao Tome and Principe','su'=>'USSR (former)','sv'=>'El Salvador','sy'=>'Syria','sz'=>'Swaziland','tc'=>'Turks and Caicos Islands','td'=>'Chad','tf'=>'French Southern Territories','tg'=>'Togo','th'=>'Thailand','tj'=>'Tajikistan','tk'=>'Tokelau','tm'=>'Turkmenistan','tn'=>'Tunisia','to'=>'Tonga','tp'=>'East Timor','tr'=>'Turkey','tt'=>'Trinidad and Tobago','tv'=>'Tuvalu','tw'=>'Taiwan','tz'=>'Tanzania','ua'=>'Ukraine','ug'=>'Uganda','uk'=>'United Kingdom','um'=>'US Minor Outlying Islands','us'=>'United States','uy'=>'Uruguay','uz'=>'Uzbekistan','va'=>'Vatican City State','vc'=>'Saint Vincent and the Grenadines','ve'=>'Venezuela','vg'=>'Virgin Islands (British)','vi'=>'Virgin Islands (US)','vn'=>'Viet Nam','vu'=>'Vanuatu','wf'=>'Wallis and Futuna Islands','ws'=>'Samoa','ye'=>'Yemen','yt'=>'Mayotte','yu'=>'Yugoslavia','za'=>'South Africa','zm'=>'Zambia','zr'=>'Zaire','zw'=>'Zimbabwe','Unknown'=>'Unknown');



	global $m_name;

	$m_name = array('','一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');



	

?>