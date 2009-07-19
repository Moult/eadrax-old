<?php
/**
 * Eadrax
 *
 * Eadrax is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Eadrax is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *                                                                                
 * You should have received a copy of the GNU General Public License
 * along with Eadrax; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category	Eadrax
 * @package		Dashboard
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Access the dashboard and dashboard related items.
 *
 * @category	Eadrax
 * @package		Dashboard
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Dashboard_Controller extends Core_Controller {
	/**
	 * This is the main dashboard for all users.
	 *
	 * @return null
	 */
	public function index()
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$user_model = new User_Model;
		$track_model = new Track_Model;
		$subscribe_model = new Subscribe_Model;
		$project_model = new Project_Model;

		// Load the view.
		$dashboard_view = new View('dashboard');

		// Send some basic variables to the view.
		$dashboard_view->username = $this->username;

		// Create the "tracking" widget.
		$dashboard_tracking_view = new View('dashboard_tracking');
		$dashboard_tracking_view->total = $track_model->track($this->uid);
		$track_uids = $track_model->track_list($this->uid);
		$track_list = array();

		// Track uids only contains uids, however usernames are useful for the 
		// view too!
		foreach ($track_uids as $uid)
		{
			$username = $user_model->user_information($uid);
			$username = $username['username'];
			$track_list[] = array($uid, $username);
		}

		$dashboard_tracking_view->track_list = $track_list;

		// Create the "subscribed" widget.
		$dashboard_subscribed_view = new View('dashboard_subscribed');
		$subscribed_total = 0;
		$projects = $project_model->projects($this->uid);

		// We will not track the "Uncategorised" project as it is special.
		unset($projects[1]);

		$project_subscribe_list = array();

		foreach ($projects as $pid => $p_name)
		{
			$subscribe_uids = $subscribe_model->subscribe_list($pid);
			$subscribe_list = array();

			foreach ($subscribe_uids as $uid)
			{
				$username = $user_model->user_information($uid);
				$username = $username['username'];
				$subscribe_list[] = array($uid, $username);
			}

			$subscribed_number = $subscribe_model->subscribe($pid);
			$project_subscribe_list[$pid] = array($p_name, $subscribed_number, $subscribe_list);

			// Add up the number of subscribers
			$subscribed_total = $subscribed_total + $subscribed_number;
		}

		// Set all the information we gathered...
		$dashboard_subscribed_view->project_subscribe_list = $project_subscribe_list;
		$dashboard_subscribed_view->total = $subscribed_total;

		// Create the "popular_projects" widget.
		$dashboard_popular_projects_view = new View('dashboard_popular_projects');

		// Generate the content.
		$this->template->content = array($dashboard_view, $dashboard_tracking_view, $dashboard_subscribed_view, $dashboard_popular_projects_view);
	}

	/**
	 * Draws a pie chart of popular projects based on subscribers for user $uid
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function popular_project_subscribers($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$subscribe_model = new Subscribe_Model;

		// Calculate the information needed in the graph.
		$projects = $project_model->projects($uid);

		// We will not track the "Uncategorised" project as it is special.
		unset($projects[1]);

		$project_subscribe_list = array();
		$project_name_list = array();

		foreach ($projects as $pid => $p_name)
		{
			$subscribed_number = $subscribe_model->subscribe($pid);
			$project_subscribe_list[] = $subscribed_number;
			$project_name_list[] = $p_name .' ('. $subscribed_number .')';
		}

		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($project_subscribe_list, 'Serie1');
		$DataSet->AddPoint($project_name_list, 'Serie2');
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie('Serie2');

		// Initialise the graph
		$Test = new pChart(410,200);  
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);  
		$Test->drawRoundedRectangle(5,5,405,195,5,230,230,230); 

		// Draw the pie chart
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),160,90,110,PIE_PERCENTAGE,TRUE,50,20,5);  
		$Test->drawPieLegend(310,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250); 

		//$Test->Render('example10.png');
		$Test->Stroke('example10.png');
	}


	/**
	 * Draws a random testing graph
	 *
	 * DEMO CODE FOR pChart CLASS. WILL REMOVE LATER.
	 */
	public function graph()
	{
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		//$DataSet->ImportFromCSV('./application/vendor/pchart/Sample/datawithtitle.csv',",",array(1,2,3),TRUE,0);
		$DataSet->AddPoint(array(1,4,2,3,1,4,2,0,4,5,6,3));
		$DataSet->AddSerie();
		$DataSet->SetSerieName('Sample Data', 'Serie1');
		//$DataSet->AddAllSeries();
		//$DataSet->SetAbsciseLabelSerie();

		// Initialise the graph
		$Test = new pChart(700,230);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->setGraphArea(60,30,680,200);
		$Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
		$Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
		$Test->drawGraphArea(255,255,255);
		$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
		$Test->drawGrid(4,220,220,220);

		// Draw the 0 line
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',6);
		$Test->drawTreshold(0,143,55,72,TRUE,TRUE);

		// Draw the filled line graph
		$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

		// Finish the graph
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawLegend(65,35,$DataSet->GetDataDescription(),255,255,255);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',10);
		$Test->drawTitle(60,22,"Example 6",50,50,50,585);
		$Test->Stroke("example6.png");
	}
}
