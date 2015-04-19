<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html;charset=UTF-8');
error_reporting(0);
date_default_timezone_set('America/Los_Angeles');

$address = $city = $state = "";
$address = urlencode($_GET['address']);
$citystatezip = urlencode($_GET['city'].', '.$_GET['state']);

if(!($address=="") || ($city=="") || ($state==""))
{
$url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1dxh3uhnf2j_55f1z&address=".$address."&citystatezip=".$citystatezip."&rentzestimate=true";

echo $url;

$xml = simplexml_load_file($url);
$xml1 = $xml->response->results;

$json = "";

if ($xml->message->code == "0"){



$xml2 = <<<XML
<results>
<result>
</result>
<chart>
</chart>
</results>
XML;

$zpid = $xml1->result->zpid;
$xml2 = new SimpleXMLElement($xml2);

$xml2->result->addChild($xml1->result->links->homedetails->getName(),$xml1->result->links->homedetails);
$xml2->result->addChild($xml1->result->address->street->getName(),$xml1->result->address->street);
$xml2->result->addChild($xml1->result->address->city->getName(),$xml1->result->address->city);
$xml2->result->addChild($xml1->result->address->state->getName(),$xml1->result->address->state);
$xml2->result->addChild($xml1->result->address->zipcode->getName(),$xml1->result->address->zipcode);
$xml2->result->addChild($xml1->result->address->latitude->getName(),$xml1->result->address->latitude);
$xml2->result->addChild($xml1->result->address->longitude->getName(),$xml1->result->address->longitude);
$xml2->result->addChild($xml1->result->useCode->getName(),$xml1->result->useCode);
$xml2->result->addChild($xml1->result->lastSoldPrice->getName(),number_format((float)$xml1->result->lastSoldPrice,2));
$xml2->result->addChild($xml1->result->yearBuilt->getName(),$xml1->result->yearBuilt);
$xml2->result->addChild($xml1->result->lastSoldDate->getName(),date('d-M-Y', strtotime($xml1->result->lastSoldDate)));
$xml2->result->addChild($xml1->result->lotSizeSqFt->getName(),number_format((float)$xml1->result->lotSizeSqFt,2));
$xml2->result->addChild("estimateLastUpdate",date('d-M-Y', strtotime($xml1->result->zestimate->{'last-updated'})));
$xml2->result->addChild("estimateAmount",number_format((float)$xml1->result->zestimate->amount,2));
$xml2->result->addChild($xml1->result->finishedSqFt->getName(),number_format((float)$xml1->result->finishedSqFt,2));


$a = $xml1->result->zestimate->valueChange;
if($a<0)
$a = "-";
else
$a = "+";
$xml2->result->addChild("estimateValueChangeSign",$a);
$xml2->result->addChild("imgn","http://www-scf.usc.edu/~csci571/2014Spring/hw6/down_r.gif");
$xml2->result->addChild("imgp","http://www-scf.usc.edu/~csci571/2014Spring/hw6/up_g.gif");

$num1 = floatval($xml1->result->zestimate->valueChange);
if($num1 < 0){
$num1 *=(-1);
$num1 = number_format((float)$num1,2);
}													
else
$num1 = number_format((float)$num1,2);	

$xml2->result->addChild("estimateValueChange",$num1);
$xml2->result->addChild($xml1->result->bathrooms->getName(),$xml1->result->bathrooms);
$xml2->result->addChild("estimateValuationRangeLow",number_format((float)$xml1->result->zestimate->valuationRange->low,2));
$xml2->result->addChild("estimateValuationRangeHigh",number_format((float)$xml1->result->zestimate->valuationRange->high,2));
$xml2->result->addChild($xml1->result->bedrooms->getName(),$xml1->result->bedrooms);
$xml2->result->addChild("restimateLastUpdate",date('d-M-Y', strtotime($xml1->result->rentzestimate->{'last-updated'})));
$xml2->result->addChild("restimateAmount",number_format((float)$xml1->result->rentzestimate->amount,2));
$xml2->result->addChild($xml1->result->taxAssessmentYear->getName(),$xml1->result->taxAssessmentYear);

$b = $xml1->result->rentzestimate->valueChange;
if($b<0)
$b = "-";
else
$b = "+";
$xml2->result->addChild("restimateValueChangeSign",$b);

$num2 = floatval($xml1->result->rentzestimate->valueChange);
if($num2 < 0){
$num2 *=(-1);
$num2 = number_format((float)$num2,2);
}													
else
$num2 = number_format((float)$num2,2);

$xml2->result->addChild("restimateValueChange",$num2);
$xml2->result->addChild($xml1->result->taxAssessment->getName(),number_format((float)$xml1->result->taxAssessment,2));
$xml2->result->addChild("restimateValuationRangeLow",number_format((float)$xml1->result->rentzestimate->valuationRange->low,2));
$xml2->result->addChild("restimateValuationRangeHigh",number_format((float)$xml1->result->rentzestimate->valuationRange->high,2));
$xml2->chart->addChild("year1",htmlspecialchars("http://www.zillow.com/app?chartDuration=1year&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=").$zpid);
$xml2->chart->addChild("years5",htmlspecialchars("http://www.zillow.com/app?chartDuration=5years&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=").$zpid);
$xml2->chart->addChild("years10",htmlspecialchars("http://www.zillow.com/app?chartDuration=10years&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=").$zpid);


$json = json_encode($xml2);


}

var_dump($json);

}
?>
