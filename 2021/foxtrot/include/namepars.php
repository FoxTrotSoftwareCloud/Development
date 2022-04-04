<?php
//-------------------------------------------------------------
// PROGRAM....: NamePars.prg
// DATE.......: August 18, 2000
// PURPOSE....: Break out names from a string of words 
// NOTES......: Written to convert Southwest's shitty 
//			   data
//			~ assume the names are first name last name order
//			OR
//			~ if (there is a comma then assume "last, first"
//			  format
//			~ Assigns First,Middle,Last Name to the PARAMETER
//			  variables being passed
//-------------------------------------------------------------*
function nameParse($nameString = '', $taxId = ''){
    $stringLen = $numberOfWords = $wordKey = $newWord = 0;
    $char = $firstName = $middleName = $lastName = '';
    $suffixes = ['JR', 'SR', 'III', 'II'];
    $arWords = [];
    $nameString = strtoupper(trim($nameString));

    // Remove suffixes
    foreach ($suffixes AS $suffix){
        $nameString = trim(str_replace($suffix, '', $nameString));
    }
    // take the 'THE' in front family trust names *
    if (strpos($nameString, 'THE ')===1 AND strpos($nameString, ' TRUST')!==false){
        $nameString = substr($nameString, 4);
    }
    if (substr($nameString, -1, 1)=='&' OR substr($nameString, 1, 1)=='&'){
        $nameString = trim(str_replace('&', '', $nameString));
    }
    $nameString = str_replace('A/C', '', $nameString);
    $nameString = str_replace('C/F', '', $nameString);
    $nameString = str_replace('R/O', '', $nameString);
    $nameString = str_replace('ROLLOVER', '', $nameString);
    $nameString = str_replace('IRA ', '', $nameString);
    $nameString = str_replace('SEP-IRA', '', $nameString);
    $nameString = str_replace('403B-7', '', $nameString);
    $nameString = str_replace('403B', '', $nameString);
    $nameString = str_replace('403-B', '', $nameString);
    $nameString = str_replace('JTWROS', '', $nameString);
    $nameString = str_replace('JT WROS', '', $nameString);
    $nameString = str_replace('CONV ', '', $nameString);
    $nameString = str_replace('CONTRIB ', '', $nameString);
    $nameString = str_replace('CUST ', '', $nameString);
    $nameString = str_replace('TEN COMM', '', $nameString);
    $nameString = str_replace('ROTH ', '', $nameString);
    $nameString = str_replace('FBO ', '', $nameString);
    $nameString = str_replace('TRUSTEE ', '', $nameString);
    $nameString = str_replace('TTEE ', '', $nameString);
    $nameString = str_replace('LIVING TRUST ', '', $nameString);
    $nameString = str_replace('ATTN ', '', $nameString);
    $nameString = str_replace('ATTN: ', '', $nameString);

    $arWords = explode(' ', $nameString);
    $numberOfWords = count($arWords);

    switch ($numberOfWords){
    case 0:
        break;
    case 1:
        $lastName = $arWords[0];
        break;
    case 2:
        $firstName = $arWords[0];
        $lastName = $arWords[1];
        break;
    case 3:
        $firstName  = $arWords[0];
        $middleName = $arWords[1];
        $lastName   = $arWords[2];
        break;
    default:
        $firstName  = $arWords[0];
        
        foreach ($arWords AS $key=>$word){
            if ($key > 0){
                if ($key==1 AND strlen($word)==1){
                    $middleName = $word;
                } else {
                    $lastName = $lastName.(empty($lastName) ? '' : ' ').$word;
                }
            }
        }
    }

    foreach ($arWords AS $wordKey=>$word){
        if ($numberOfWords == 1){
            $lastName = $arWords[0];
        } else if (
            strpos($nameString, "BANK")!==false
            OR strpos($nameString, "CREDIT")!==false  
            OR strpos($nameString, "INCORPORATED")!==false 
            OR strpos($nameString," INC")!==false 
            OR strpos($nameString,",INC")!==false 
            OR strpos($nameString, ",CO")!==false 
            OR strpos($nameString." ", " CO ")!==false 
            OR strpos($nameString, "CO.")!==false 
            OR strpos($nameString, "COMPANY")!==false 
            OR strpos($nameString, "COUNTY")!==false 
            OR strpos($nameString, "CTY")!==false 
            OR strpos($nameString,"CLUB")!==false
            OR strpos($nameString,"CHURCH")!==false 
            OR strpos($nameString,"SCHOOL")!==false 
            OR strpos($nameString,"UNIV")!==false 
            OR strpos($nameString,"UNIVERSITY")!==false 
            OR strpos($nameString,"CITY")!==false 
            // OR strpos($nameString,"CR")!==false 
            OR strpos($nameString,"HOSPITAL")!==false 
            OR strpos($nameString,"CLINIC")!==false 
            OR strpos($nameString,"FARMS")!==false 
            OR strpos($nameString,"FUND")!==false 
            OR strpos($nameString,"FEDERAL")!==false 
            OR strpos($nameString,"ASSOC")!==false 
            OR strpos($nameString,"CORP")!==false 
            OR strpos($nameString,"LLC")!==false 
            OR strpos($nameString,",LLC")!==false 
            OR strpos($nameString,"LIMITED")!==false 
            OR strpos($nameString,"LTD")!==false 
            OR strpos($nameString,"CENTER")!==false 
            OR strpos($nameString,"ENTERPRISES")!==false 
            OR strpos($nameString,"INVESTORS")!==false 
            OR strpos($nameString,"CHAMBER OF")!==false 
            OR substr($word, 3, 1) == '-'
        ){
            $firstName = $middleName = '';
            $lastName = $nameString;
            break;
        } else if ($word == '&'){
            $middleName = '';
            
            if ($wordKey == 0){
                // * Do nothing *
            } else if ($wordKey == 1){
                $firstName = $arWords[$wordKey-1].' & ';
                
                if ($numberOfWords > 4){
                    $firstName = $firstName.' '.$arWords[$wordKey+2];
                    $lastName = $arWords[$wordKey+3];
                } else {
                    $lastName = $arWords[$numberOfWords-1];
                }
            } else if ($wordKey == 2){
                $firstName = $arWords[$wordKey-2].' ' .$arWords[$wordKey-1]
                                .' & '
                                .$arWords[$wordKey+1];
                                
                if ($numberOfWords > 5){
                    $firstName = $firstName.' ' .$arWords[$wordKey+2];
                    $lastName = $arWords[$wordKey+3];
                } else {
                    $lastName = $arWords[$numberOfWords-1];
                }
            }
        } else if (strpos($word, 'ESTATE')!==false){
            if ($wordKey == 0){
                $firstName = '';
                $lastName = $word;
            } else if ($wordKey == 1){
                // $lastName = $arWords[$wordKey-1].' '.$word.' ESTATE';
                $firstName = "";
                $lastName  = $arWords[$wordKey-1].' ESTATE';
            } else if ($wordKey > 1){
                // Assume the last name of the Estate precedes "ESTATE"
                $lastName = $arWords[$wordKey-1].' ESTATE';
                
                for ($i=0; $i < $wordKey-1; $i++){
                    $firstName .= (empty($firstName)?'':' ').$arWords[$i];
                }
            }
        } else if (strpos($word, ' TRUST')!==false){ 
            // * add 'trust' to the end of the name *
            if ($wordKey <> 0){
                if (
                    $arWords[$wordKey-1] == 'FAMILY'
                    OR $arWords[$wordKey-1] == 'FAM'
                    OR $arWords[$wordKey-1] == 'REVOCABLE'
                    OR $arWords[$wordKey-1] == 'REV'
                    OR $arWords[$wordKey-1] == 'IRREV'
                    OR $arWords[$wordKey-1] == 'IRREVOCABLE'
                    OR $arWords[$wordKey-1] == 'BP'
                    OR $arWords[$wordKey-1] == 'LIVING'
                ){
                    if ($wordKey > 1) {
                        // *!*$firstName  = ''
                        $middleName = '';
                        $lastName = $arWords[$wordKey-2].' '.$arWords[$wordKey-1].' TRUST';
                    } else if ($wordKey == 1){
                        $lastName = $arWords[$wordKey-1].' '.$arWords[$wordKey].' TRUST';
                    }
                } 
            } else {
                if ($numberOfWords > 1){
                    $lastName = $arWords[$wordKey-1].' TRUST';
                } else {
                    $lastName = $arWords[0].' TRUST';
                }
            }
            // * name of a company or bank or credit union 
            // * put the whole name into the last name field
        } else if (substr($word,-1,1)==',' AND $wordKey==0){
            // * case the string is "Last, First M" format
            if ($numberOfWords >= $wordKey + 1){
                $firstName = $arWords[$wordKey+1];
            }
            
            if ($numberOfWords >= $wordKey + 2){
                $middleName = ($numberOfWords>2 ? $arWords[$wordKey+2] : '');
            }
            
            $lastName = str_replace(',', '', $word);
        }
    }

    return ['first_name'=>$firstName, 'mi'=>$middleName, 'last_name'=>$lastName];
}



// 	*------------------------------*
// 	* CYCLE THROUGH EACH CHARACTER *
// 	* AND FILL THE ARRAY		   *
// 	*------------------------------*


// 		*--------------------------------------*
// 		* ASSIGN NAMES TO THE PARAMETER FIELDS *
// 		*--------------------------------------*

// * cycle through the words *
// FOR $wordKey = 1 TO $numberOfWords
// 	DO CASE
// 		CASE $numberOfWords = 1
// 			$lastName = $arWords[1]
// 		CASE $arWords[$wordKey] = '&'
// 			$middleName = ''
			
// 			DO CASE
// 				CASE $wordKey = 1
// 					* Do nothing *
					
// 				CASE $wordKey = 2
// 					$firstName = $arWords[$wordKey-1] + ' & '
					
// 					if ($numberOfWords > 4
// 						$firstName = $firstName + ' ' + $arWords[$wordKey+2]
// 						$lastName = $arWords[$wordKey+3]
// 					ELSE
// 						$lastName = $arWords[$numberOfWords]
// 					}
// 				CASE $wordKey = 3
// 					$firstName = $arWords[$wordKey-2] + ' ' + $arWords[$wordKey-1] ;
// 								+ ' & ' ;
// 								+ $arWords[$wordKey+1]
					
// 					if ($numberOfWords > 5
// 						$firstName = $firstName + ' ' + $arWords[$wordKey+2]
// 						$lastName = $arWords[$wordKey+3]
// 					ELSE
// 						$lastName = $arWords[$numberOfWords]
// 					}
// 			ENDCASE

			
// 		* case the string is "Last, First M" format
// 		CASE RIGHT($arWords[$wordKey],1)=',' AND $wordKey=1
// 			if ($numberOfWords >= $wordKey + 1
// 				$firstName = $arWords[$wordKey+1]
// 			}
			
// 			if ($numberOfWords >= $wordKey + 2
// 				$middleName = IIF($numberOfWords>2,arWords[$wordKey+2],'')
// 			}
			
// 			$lastName = str_replace($arWords[$wordKey],',','')
		
// 		* estate
// 		CASE ' ESTATE ' $ ' ' + $arWords[$wordKey] + ' '
// 				DO CASE
// 					CASE $numberOfWords = 1
// 						* Do nothing
// 					CASE $wordKey = 2
// 						*!* $lastName = $arWords[$wordKey-1] + ' ' + $arWords[$wordKey] ;
// 								   + ' ESTATE'
// 						$firstName = ""
// 						$lastName  = $arWords[$wordKey-1] + ' ESTATE'
// 					CASE $wordKey = 3
// 						*!* $firstName = ''
// 						$lastName = $arWords[$wordKey-2] + ' ' + $arWords[$wordKey-1] ;
// 								+ ' ESTATE'
// 					CASE $wordKey > 3
// 				ENDCASE

// 		* add 'trust' to the end of the name *
// 		CASE ' TRUST ' $ ' ' + $arWords[$wordKey] + ' '

// 			if ($wordKey <> 1
// 				if ($arWords[$wordKey-1] = 'FAMILY' ;
// 				 OR $arWords[$wordKey-1] = 'FAM'  ;
// 				 OR $arWords[$wordKey-1] = 'REVOCABLE'  ;
// 				 OR $arWords[$wordKey-1] = 'REV' ;
// 				 OR $arWords[$wordKey-1] = 'IRREV' ;
// 				 OR $arWords[$wordKey-1] = 'IRREVOCABLE' ;
// 				 OR $arWords[$wordKey-1] = 'BP' ;
// 				 OR $arWords[$wordKey-1] = 'LIVING'  
				
// 					DO CASE
// 						CASE $wordKey > 2
// 	*!*							$firstName  = ''
// 							$middleName = ''
// 							$lastName = $arWords[$wordKey-2] + ' ' + $arWords[$wordKey-1] ;
// 									+ ' TRUST'
// 						CASE $wordKey = 2
// 							$lastName = $arWords[$wordKey-1] + ' ' + $arWords[$wordKey] ;
// 									+ ' TRUST'
// 					ENDCASE
// 				ELSE
// 					if ($numberOfWords > 1
// 						$lastName = $arWords[$wordKey-1] + ' TRUST'
// 					ELSE
// 						$lastName = $arWords[1] + ' TRUST'
// 					}
// 				}
// 			ENDif ( && $wordKey <> 1
			
// 		* name of a company or bank or credit union 
// 		* put the whole name into the last name field
// 		CASE " BANK " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CREDIT " $ ' ' + $arWords[$wordKey] + ' '  ;
// 		  OR " INC " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CO " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " COMPANY " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " COUNTY " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CTY " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CLUB " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CHURCH " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " SCHOOL " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " UNIV " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " UNIVERSITY " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CITY " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CR " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " HOSPITAL " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CLINIC " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " FARMS " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " FUND " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " FEDERAL " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " ASSOC " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CORP " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " LLC " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " LIMITED " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " LTD " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CENTER " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " ENTERPRISES " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " INVESTORS " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR " CHAMBER OF " $ ' ' + $arWords[$wordKey] + ' ' ;
// 		  OR SUBSTR($arWords[$wordKey],3,1) = '-'
			  
// 			STOR '' TO $firstName,$middleName
// 			$lastName = $nameString
// 	ENDCASE
	
// NEXT $wordKey

// RETURN
?>