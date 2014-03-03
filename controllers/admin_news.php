<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_News extends Admin_Controller {
/**
 * News Rss
 * 
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
	public function __construct()
	{
		parent::__construct();
			
		$this->load->model('problemlog/problemlog_m');
	}
	
	public function index()
	{
		header("Access-Control-Allow-Origin: http://example.com");


// Website url to open
		$url = 'http://feeds.feedburner.com/Jquery4u';
		echo $this->reader1($url);

// Get that website's content
		
	}
	public function jquery()
	{
		$url = "http://feeds.feedburner.com/jquery/";
		echo $this->reader1($url);
	}
	
	public function stack_overflow_codeigniter()
	{
		$url = "http://stackoverflow.com/feeds/tag?tagnames=php+codeigniter&sort=newest";
		echo $this->reader2($url);
	}
	
	
	public function stack_overflow_pyrocms()
	{
		$url = "http://stackoverflow.com/feeds/tag?tagnames=php+codeigniter+pyrocms&sort=newest";
		echo $this->reader2($url);
	}
	public function reader1($url)
	{
		$xml ="";
		$content = file_get_contents($url);
		if(!$content){
			$xml ="";
		}else{
			$xml = new SimpleXMLElement($content);			
		}
		
		
		foreach ($xml->channel->item as $entry) {
				echo "<li><a href=\"".$entry->link."\"><h1 style=color:#ED8E28>".$entry->title."</h1></a></li>";
				echo "<li>".$entry->description."</li><hr/>";
				
		}

	}
	
	public function problemlog()
	{
		$problem_log = $this->problemlog_m->limit(10)->get_Problemlog_all();
		foreach($problem_log as $problem)
		{
			echo "<li><h1 style=color:#ED8E28>".$problem->title."</h1></li>";
			echo "<li>".$problem->detail."</li>";
		}
	}
	
	public function reader2($url)
	{
		$xml ="";
		$detail = "";
		$content = file_get_contents($url);
		$xmlcontent = new SimpleXMLElement($content);
		// /print_r($xmlcontent);
		foreach($xmlcontent as $feed)
		{
				
			
				if($feed->title!=""){
					echo   "<li><a href=\"".$feed->link['href']."\"><h1 style=color:#ED8E28 >".$feed->title."</h1></a></li>";
					echo "<li>".$feed->summary."</li><hr/>";
					
				}
				
		}
			//echo $detail;
			
		
	}
	
}
	