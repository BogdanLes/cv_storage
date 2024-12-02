
<?php

class Country {
	private $country;
	
	public function __construct($icountry)
	{
		$this->country=$icountry;
	}
	
	public function get_country() { return $this->country; }
	
	public function set_country($icountry) { $this->country=$icountry; }
	
	public static function load_db_country($dbfile)
	{
		$adbc=[];
		$k=0;
		$xmlc= simplexml_load_file($dbfile);
		$nrcountries= (int) $xmlc->countrynr;

		$db_country=$xmlc->dbcountry;
		foreach($db_country->children() as $itemcountry)
		{
			$country_name=$itemcountry;
			$country_obj= new Country($itemcountry);
			$adbc[$k]=$country_obj;
			$k++;
		}
		return $adbc;
	}
	
	//for sorting array
	public static function sort_country($acy) 
	{
		usort($acy, function($a, $b) {return strcmp($a->get_country(), $b->get_country());});
		return $acy;
	}
	
	//for adding a new country to the database, verifie if it exists
	public static function add_new_country($dbfile, $newcountry)
	{
		$vcountry=strtolower($newcountry);
		$xmlc=simplexml_load_file($dbfile);
		$nrcountries=(int) $xmlc->countrynr;
		
		$db_country=$xmlc->dbcountry;
		foreach($db_country->children() as $itemcountry)
		{
			$name_country=$itemcountry;
			$name_country=strtolower($name_country);
			if($name_country==$vcountry)
			{
				return false;
			}
		}
		
		$nrcountries=$nrcountries+1;
		$xmlc->countrynr=$nrcountries;
		$new_node="country".$nrcountries;
		$db_country->addChild($new_node, $newcountry);
		
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xmlc->asXML());
		$dom->save($dbfile);

		return true;
	}
	
}


class Company extends Country {
	private $db_nr=0;
	private $name;
	private $linkc;
	private $contact;
	private $obs;
	private $datecv;
	private $status_cv="on";
	private $interview="no";
	
	
	public function get_db_nr() { return $this->db_nr; }
	public function set_db_nr($ldb_nr) { $this->db_nr=$ldb_nr; }
	public function get_name() { return $this->name; }
	public function set_name($lname) { $this->name=$lname; }
	public function get_linkc() { return $this->linkc; }
	public function set_linkc($llinkc) { $this->linkc=$llinkc; }
	public function get_contact() { return $this->contact; }
	public function set_contact($lcontact) { $this->contact=$lcontact; }
	public function get_obs() { return $this->obs; }
	public function set_obs($lobs) { $this->obs=$lobs; }
	public function get_datecv() { return $this->datecv; }
	public function set_datecv($ldatecv) { $this->datecv=$ldatecv; }
	public function get_status_cv() { return $this->status_cv; }
	public function set_status_cv($lstatus_cv) { $this->status_cv=$lstatus_cv; }
	public function get_interview() { return $this->interview; }
	public function set_intetview($linterview) { $this->interview=$linterview; }
	
	public function __construct($ldb_nr, $lname, $llinkc, $lcontact, $lobs, $ldatecv, $lcountry, $lstatus_cv, $linterview)
	{
		$this->db_nr=$ldb_nr;
		$this->name=$lname;
		$this->linkc=$llinkc;
		$this->contact=$lcontact;
		$this->obs=$lobs;
		$this->datecv=$ldatecv;
		$this->status_cv=$lstatus_cv;
		$this->interview=$linterview;
		
		parent::__construct($lcountry);
	}
	
	public function add_new_cv($db_companies)
	{
		$xml=simplexml_load_file($db_companies);
		$nr_cv=(int)$xml->cvnr;
		$nr_cv=$nr_cv+1;
		$xml->cvnr=$nr_cv;
		
		$new_node="bss".$nr_cv;
		$acv=$xml->database->addChild($new_node,'');
		$acv->addChild("cvnr", $nr_cv);
		$acv->addChild("bss", $this->get_name());
		$acv->addChild("link", $this->get_linkc());
		$acv->addChild("contact", $this->get_contact());
		$acv->addChild("obs", $this->get_obs());
		$acv->addChild("date", $this->get_datecv());
		$acv->addChild("country", $this->get_country());
		$acv->addChild("status", $this->get_status_cv());
		$acv->addChild("interview", $this->get_interview());
		
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$dom->save($db_companies);
	}
	
	static function load_last_cv($dbfile)
	{
		$xmlcv=simplexml_load_file($dbfile);
		$nrbss=$xmlcv->cvnr;
		$nod_date="bss".$nrbss;	
		$last_date=$xmlcv->database->$nod_date->date;
		$vlast_date=(string)$last_date;
		$acv=[];
		$k=0;
		
		$db_companies=$xmlcv->database;
		foreach($db_companies->children() as $itembss)
		{
			$db_nr=$itembss->cvnr;
			$name=$itembss->bss;
			$linkc=$itembss->link;
			$contact=$itembss->contact;
			$obs=$itembss->obs;
			$datecv=$itembss->date;
			$vdatecv=(string)$datecv;
			$country=$itembss->country;
			$status_cv=$itembss->status;
			$interview=$itembss->interview;
			
			if($vdatecv==$vlast_date)
			{
				$obj=new Company($db_nr, $name, $linkc, $contact, $obs, $datecv, $country, $status_cv, $interview);
				$acv[$k]=$obj;
				//$acv[$k]=new Company($db_nr, $name, $linkc, $contact, $obs, $datecv, $country, $status_cv, $interview);
				$k++;
			}
		}
		
		return $acv;
	}
	
	static function search_cv($shw, $shc, $dbfile)
	{
		$w = explode(" ", $shw);
		$ash=[];
		$k=0;
		
		$xmlcv=simplexml_load_file($dbfile);
		$db_companies=$xmlcv->database;
		
		foreach($db_companies->children() as $itembss)
		{
			$db_nr=$itembss->cvnr;
			$name=$itembss->bss;
			$linkc=$itembss->link;
			$contact=$itembss->contact;
			$obs=$itembss->obs;
			$datecv=$itembss->date;
			$vdatecv=(string)$datecv;
			$country=$itembss->country;
			$status_cv=$itembss->status;
			$interview=$itembss->interview;
			
			$txt=$name." ".$linkc." ".$contact." ".$obs." ".$datecv." ".$country." ".$status_cv." ".$interview;
			
			$search_verification=true;
			//search with Country
			if($shc!="All")
			{
				if($country==$shc)
				{
					for($i=0;$i<count($w);$i++)
					{
						if(!stristr($txt, $w[$i])) { $search_verification=false; break; }
				    }
				}
				else { $search_verification=false; }
			}
			//search for all countries
			else
			{
				for($i=0;$i<count($w);$i++)
				{
					if(!stristr($txt, $w[$i])) { $search_verification=false; break; }
				}
			}
			
			//if good search
			if($search_verification==true)
			{
				$obj=new Company($db_nr, $name, $linkc, $contact, $obs, $datecv, $country, $status_cv, $interview);
				$ash[$k]=$obj;
				$k++;
			}
		}
		
		return $ash;
	}
	
	
	public function html_generator($type="search")
	{
		if($type=="search") { $h="vsh"; }
		if($type=="add") { $h="vadd"; }
		if($type=="browse") { $h="vbr"; }
		
		$html="
		<div class='w".$h."bsscl'>
		<div class='".$h."wtitlecl'><span class='".$h."ntitlecl'>Title:</span> <span id='".$h.$this->db_nr."title' class='".$h."titlecl'>".$this->name."</span></div>
		<div class='".$h."wlinkcl'><a id='".$h.$this->db_nr."link' class='".$h."linkcl' href='".$this->linkc."' target='_blank'>".$this->linkc."</a></div>
		<div class='".$h."wcontactcl'><span class='".$h."ncontactcl'>Contact:</span> <span id='".$h.$this->db_nr."contact' class='".$h."contactcl'>".$this->contact."</span></div>
		<div class='".$h."wobscl'><span class='".$h."nobscl'>Observation:</span> <span id='".$h.$this->db_nr."obs' class='".$h."obscl'>".$this->obs."</span></div>
		<div class='".$h."wcountry_datecl'>
		<span class='".$h."ncountrycl'>Country:</span> <span id='".$h.$this->db_nr."country' class='".$h."countrycl'>".$this->get_country()."</span>
		<span class='".$h."ndatecl'>Date:</span> <span id='".$h.$this->db_nr."date' class='".$h."datecl'>".$this->datecv."</span>
		</div>
		<div class='".$h."wstatuscv_inteviewcl'>
		<span class='".$h."nstatus_cvcl'>Status:</span> <span id='".$h.$this->db_nr."status_cv' class='".$h."status_cvcl'>".$this->status_cv."</span>
		<span class='".$h."ninteviewcl'>Interview:</span> <span id='".$h.$this->db_nr."interview' class='".$h."inteviewcl'>".$this->interview."</span>
		<input type='button' id='".$h."edit".$this->db_nr."' class='".$h."editcl' onclick='ldbeditcv(".$this->db_nr.")' value='Edit' />
		</div>	
		</div>
		";
		
		return $html;
	}
	
}


class JobsIterator implements Iterator {
	private $items = [];
	private $pointer = 0;

	public function __construct($dbfile, $country_cv) {
    // array_values() makes sure that the keys are numbers
    //$this->items = array_values($items);
	$items_cv=[];
	$k=0;
	$x=simplexml_load_file($dbfile);
	$db_companies=$x->database;
	
	foreach($db_companies->children() as $itembss)
	{
		$cv_country=$itembss->country;
		if($country_cv==$cv_country)
		{
			$db_nr=$itembss->cvnr;
			$name=$itembss->bss;
			$linkc=$itembss->link;
			$contact=$itembss->contact;
			$obs=$itembss->obs;
			$datecv=$itembss->date;
			$vdatecv=(string)$datecv;
			$country=$itembss->country;
			$status_cv=$itembss->status;
			$interview=$itembss->interview;
			
			$obj=new Company($db_nr, $name, $linkc, $contact, $obs, $datecv, $country, $status_cv, $interview);
			$items_cv[$k]=$obj;
			$k++;
		}
		
	}
	$this->items = array_values($items_cv);
  }

  public function current(): Company {
    return $this->items[$this->pointer];
  }

  public function key(): int {
    return $this->pointer;
  }

  public function next(): void {
    $this->pointer++;
  }

  public function rewind(): void {
    $this->pointer = 0;
  }

  public function valid(): bool {
    // count() indicates how many items are in the list
    return $this->pointer < count($this->items);
  }
  
	//filter by Date
	public function date_filter($selected_month)
	{
		$new_cv_list=[];
		$k=0;
		while($this->valid())
		{
			$vcv=$this->current();
			$cv_date=$vcv->get_datecv();
			$cv_month=date("m",strtotime($cv_date));
			if($cv_month==$selected_month)
			{
				$new_cv_list[$k]=$vcv;
				$k++;
			}
			$this->next();
		}
		$this->items=$new_cv_list;
		$this->pointer = 0;
	}
	
	//sort results
	public function sort_results($sort_val, $sort_type)
	{
		//by Company
		if($sort_val=="Company")
		{
			if($sort_type=="Ascending")
			{
				usort($this->items, function($a, $b) { return strcmp($a->get_name(), $b->get_name()); });
			}
			else
			{
				usort($this->items, function($a, $b) { return strcmp($b->get_name(), $a->get_name()); });
			}
		}
		
		//by Date
		if($sort_val=="Date")
		{
			if($sort_type=="Ascending")
			{
				usort($this->items, function($a, $b) { return strtotime($a->get_datecv()) - strtotime($b->get_datecv()); });
			}
			else
			{
				usort($this->items, function($a, $b) { return strtotime($b->get_datecv()) - strtotime($a->get_datecv()); });
			}
		}
		
	}

	//total number of results for browse
	public function total_results() { return count($this->items); }
	
}

?>





