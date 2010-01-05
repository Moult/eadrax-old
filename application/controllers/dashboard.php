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
		$kudos_model = new Kudos_Model;
		$update_model = new Update_Model;
		$comment_model = new Comment_Model;

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

		$project_subscribe_list = array();

		foreach ($projects as $pid => $p_name)
		{
			if ($pid != 1)
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
		}

		// Set all the information we gathered...
		$dashboard_subscribed_view->project_subscribe_list = $project_subscribe_list;
		$dashboard_subscribed_view->total = $subscribed_total;

		// Create the "kudos" widget.
		$dashboard_kudos_view = new View('dashboard_kudos');
		$kudos_total = 0;

		foreach ($projects as $pid => $p_name)
		{
			$kudos_number = $kudos_model->kudos_project($pid, $this->uid);

			// Add up the number of kudos
			$kudos_total = $kudos_total + $kudos_number;
		}

		// Set all the information we gathered...
		$dashboard_kudos_view->total = $kudos_total;

		// Create the "popular_projects" widget.
		$dashboard_popular_projects_view = new View('dashboard_popular_projects');
		list($width, $height, $type, $attr) = getimagesize(url::site('dashboard/popular_project_subscribers/'. $this->uid));
		if ($width == 1)
		{
			$dashboard_popular_projects_view->error = TRUE;
		}

		// Create the "projects_activity" widget.
		$dashboard_projects_activity_view = new View('dashboard_projects_activity');
		list($width, $height, $type, $attr) = getimagesize(url::site('dashboard/projects_activity/'. $this->uid));
		if ($width == 1)
		{
			$dashboard_projects_activity_view->error = TRUE;
		}

		// Create the "update_activity" widget.
		$dashboard_update_activity_view = new View('dashboard_update_activity');

		// Create the "view_activity" widget.
		$dashboard_view_activity_view = new View('dashboard_view_activity');

		// Create the "comment_activity" widget.
		$dashboard_comment_activity_view = new View('dashboard_comment_activity');

		// Create news "newsfeed" widget.
		$dashboard_newsfeed_view = new View('dashboard_newsfeed');

		// Prepare the newsfeed.
		$newsfeed = $update_model->news($this->uid);
		$news_view = array();

		foreach($newsfeed as $news)
		{
			// The logtime should be human readable.
			$logtime = date("jS F H:i", strtotime($news->logtime));

			// The username is always useful!
			$user = $user_model->user_information($news->uid);
			$user = $user['username'];

			if (!empty($news->cid))
			{
				$comment_information = $comment_model->comment_information($news->cid);
				$news_view[] = '<b>'. $logtime .'</b> A comment "'. $comment_information['comment'] .'" (id '. $news->cid .') was made by '. $user .' (id '. $news->uid .') on the update '. $news->upid;
			}
			elseif (!empty($news->upid))
			{
				$update = $update_model->update_information($news->upid);
				$update = $update['summary'];
				$project = $project_model->project_information($news->pid);
				$project = $project['name'];
				$news_view[] = '<b>'. $logtime .'</b>'. $user .' (id '. $news->uid .') has created a new update "'. $update .'" id '. $news->upid .' of project "'. $project .'" (id '. $news->pid .')';
			}
			elseif (!empty($news->pid))
			{
				$project = $project_model->project_information($news->pid);
				$project = $project['name'];
				$news_view[] = '<b>'. $logtime .'</b>'. $user .' (id '. $news->uid .') has created a new project "'. $project .'" (id '. $news->pid .')';
			}
			elseif (!empty($news->kid))
			{
				$update = $update_model->update_information($news->kid);
				$update = $update['summary'];
				$news_view[] = '<b>'. $logtime .'</b>'. $user .' (id '. $news->uid .') has kudos\'d the update "'. $update .'" (id '. $news->kid .')';
			}
			elseif (!empty($news->sid))
			{
				$project = $project_model->project_information($news->sid);
				$project = $project['name'];
				$news_view[] = '<b>'. $logtime .'</b>'. $user .' (id '. $news->uid .') has subscribed to '. $project .' (id '. $news->sid .')';

			}
			elseif (!empty($news->tid))
			{
				$track_user = $user_model->user_information($news->tid);
				$track_user = $track_user['username'];
				$news_view[] = '<b>'. $logtime .'</b>'. $user .' (id '. $news->uid .') has started tracking '. $track_user .' (id '. $news->tid .')';
			}
		}

		$dashboard_newsfeed_view->newsfeed = $news_view;

		// Generate the content.
		$this->template->content = array($dashboard_view, $dashboard_newsfeed_view, $dashboard_update_activity_view, $dashboard_comment_activity_view, $dashboard_view_activity_view, $dashboard_tracking_view, $dashboard_subscribed_view, $dashboard_popular_projects_view, $dashboard_kudos_view, $dashboard_projects_activity_view);
	}

	/**
	 * Draws a line chart of viewing activity for user $uid
	 *
	 * This graph shows the number of times people have viewed content created 
	 * by the user $uid.
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function view_activity($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$update_model = new Update_Model;

		// Calculate the time ranges 5 weeks into the past.
		$week8_end = date("Y-m-d", strtotime("last Monday"));
		$week8_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_end)));
		$week7_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_start)));
		$week6_start = date("Y-m-d", strtotime("last Monday", strtotime($week7_start)));
		$week5_start = date("Y-m-d", strtotime("last Monday", strtotime($week6_start)));
		$week4_start = date("Y-m-d", strtotime("last Monday", strtotime($week5_start)));
		$week3_start = date("Y-m-d", strtotime("last Monday", strtotime($week4_start)));
		$week2_start = date("Y-m-d", strtotime("last Monday", strtotime($week3_start)));
		$week1_start = date("Y-m-d", strtotime("last Monday", strtotime($week2_start)));

		$view_list = array();
		$view_list[] = $update_model->view_number_time($uid, $week1_start, $week2_start) + $project_model->view_number_time($uid, $week1_start, $week2_start);
		$view_list[] = $update_model->view_number_time($uid, $week2_start, $week3_start) + $project_model->view_number_time($uid, $week2_start, $week3_start);
		$view_list[] = $update_model->view_number_time($uid, $week3_start, $week4_start) + $project_model->view_number_time($uid, $week3_start, $week4_start);
		$view_list[] = $update_model->view_number_time($uid, $week4_start, $week5_start) + $project_model->view_number_time($uid, $week4_start, $week5_start);
		$view_list[] = $update_model->view_number_time($uid, $week5_start, $week6_start) + $project_model->view_number_time($uid, $week5_start, $week6_start);
		$view_list[] = $update_model->view_number_time($uid, $week6_start, $week7_start) + $project_model->view_number_time($uid, $week6_start, $week7_start);
		$view_list[] = $update_model->view_number_time($uid, $week7_start, $week8_start) + $project_model->view_number_time($uid, $week7_start, $week8_start);
		$view_list[] = $update_model->view_number_time($uid, $week8_start, $week8_end) + $project_model->view_number_time($uid, $week8_start, $week8_end);

		// Reformat the dates to show in the graph nicely.
		$date_array = array(
			date("d/m", strtotime($week2_start)),
			date("d/m", strtotime($week3_start)),
			date("d/m", strtotime($week4_start)),
			date("d/m", strtotime($week5_start)),
			date("d/m", strtotime($week6_start)),
			date("d/m", strtotime($week7_start)),
			date("d/m", strtotime($week8_start)),
			date("d/m", strtotime($week8_end))
		);


		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($view_list,"Serie1");
		$DataSet->AddSerie('Serie1');
		$DataSet->AddPoint($date_array,"XLabel");
		$DataSet->SetAbsciseLabelSerie("XLabel");
		$DataSet->SetYAxisName("Views");

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('view_activity_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->setGraphArea(60,30,390,170);
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);
		$Test->drawRoundedRectangle(5,5,405,195,5,200,200,200);
		$Test->drawGraphArea(240,240,240);
		$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
		$Test->drawGrid(4,220,220,220);

		// Draw the 0 line
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',6);
		$Test->drawTreshold(0,143,55,72,TRUE,TRUE);

		// Draw the line graph
		$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
		$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

		// Finish the graph
		$Cache->WriteToCache('view_activity_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
	}

	/**
	 * Draws a line chart of comment activity for user $uid
	 *
	 * This graph shows comments directed at the user $uid as well as the 
	 * comments created by the user $uid.
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function comment_activity($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$comment_model = new Comment_Model;

		// Calculate the time ranges 5 weeks into the past.
		$week8_end = date("Y-m-d", strtotime("last Monday"));
		$week8_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_end)));
		$week7_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_start)));
		$week6_start = date("Y-m-d", strtotime("last Monday", strtotime($week7_start)));
		$week5_start = date("Y-m-d", strtotime("last Monday", strtotime($week6_start)));
		$week4_start = date("Y-m-d", strtotime("last Monday", strtotime($week5_start)));
		$week3_start = date("Y-m-d", strtotime("last Monday", strtotime($week4_start)));
		$week2_start = date("Y-m-d", strtotime("last Monday", strtotime($week3_start)));
		$week1_start = date("Y-m-d", strtotime("last Monday", strtotime($week2_start)));

		$comment_by_list = array();
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week1_start, $week2_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week2_start, $week3_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week3_start, $week4_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week4_start, $week5_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week5_start, $week6_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week6_start, $week7_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week7_start, $week8_start);
		$comment_by_list[] = $comment_model->comment_number_time($uid, $week8_start, $week8_end);

		$comment_for_list = array();
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week1_start, $week2_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week2_start, $week3_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week3_start, $week4_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week4_start, $week5_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week5_start, $week6_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week6_start, $week7_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week7_start, $week8_start);
		$comment_for_list[] = $comment_model->comment_for_number_time($uid, $week8_start, $week8_end);


		// Reformat the dates to show in the graph nicely.
		$date_array = array(
			date("d/m", strtotime($week2_start)),
			date("d/m", strtotime($week3_start)),
			date("d/m", strtotime($week4_start)),
			date("d/m", strtotime($week5_start)),
			date("d/m", strtotime($week6_start)),
			date("d/m", strtotime($week7_start)),
			date("d/m", strtotime($week8_start)),
			date("d/m", strtotime($week8_end))
		);


		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($comment_by_list,"Serie1");
		$DataSet->AddPoint($comment_for_list,"Serie2");
		$DataSet->AddSerie('Serie1');
		$DataSet->AddSerie('Serie2');
		$DataSet->AddPoint($date_array,"XLabel");
		$DataSet->SetSerieName('By you', 'Serie1');
		$DataSet->SetSerieName('For you', 'Serie2');
		$DataSet->SetAbsciseLabelSerie("XLabel");
		$DataSet->SetYAxisName("Comments");

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('comment_activity_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->setGraphArea(60,30,390,170);
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);
		$Test->drawRoundedRectangle(5,5,405,195,5,200,200,200);
		$Test->drawGraphArea(240,240,240);
		$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
		$Test->drawGrid(4,220,220,220);

		// Draw the 0 line
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',6);
		$Test->drawTreshold(0,143,55,72,TRUE,TRUE);

		// Draw the line graph
		$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
		$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

		// Finish the graph
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawLegend(65,35,$DataSet->GetDataDescription(),255,255,255);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',10);

		$Cache->WriteToCache('comment_activity_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
	}

	/**
	 * Draws a line chart of update activity for user $uid
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function update_activity($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$update_model = new Update_Model;

		// Calculate the time ranges 5 weeks into the past.
		$week8_end = date("Y-m-d", strtotime("last Monday"));
		$week8_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_end)));
		$week7_start = date("Y-m-d", strtotime("last Monday", strtotime($week8_start)));
		$week6_start = date("Y-m-d", strtotime("last Monday", strtotime($week7_start)));
		$week5_start = date("Y-m-d", strtotime("last Monday", strtotime($week6_start)));
		$week4_start = date("Y-m-d", strtotime("last Monday", strtotime($week5_start)));
		$week3_start = date("Y-m-d", strtotime("last Monday", strtotime($week4_start)));
		$week2_start = date("Y-m-d", strtotime("last Monday", strtotime($week3_start)));
		$week1_start = date("Y-m-d", strtotime("last Monday", strtotime($week2_start)));

		$activity_list = array();
		$activity_list[] = $update_model->update_number_time($uid, $week1_start, $week2_start);
		$activity_list[] = $update_model->update_number_time($uid, $week2_start, $week3_start);
		$activity_list[] = $update_model->update_number_time($uid, $week3_start, $week4_start);
		$activity_list[] = $update_model->update_number_time($uid, $week4_start, $week5_start);
		$activity_list[] = $update_model->update_number_time($uid, $week5_start, $week6_start);
		$activity_list[] = $update_model->update_number_time($uid, $week6_start, $week7_start);
		$activity_list[] = $update_model->update_number_time($uid, $week7_start, $week8_start);
		$activity_list[] = $update_model->update_number_time($uid, $week8_start, $week8_end);

		// Reformat the dates to show in the graph nicely.
		$date_array = array(
			date("d/m", strtotime($week2_start)),
			date("d/m", strtotime($week3_start)),
			date("d/m", strtotime($week4_start)),
			date("d/m", strtotime($week5_start)),
			date("d/m", strtotime($week6_start)),
			date("d/m", strtotime($week7_start)),
			date("d/m", strtotime($week8_start)),
			date("d/m", strtotime($week8_end))
		);


		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($activity_list);
		$DataSet->AddSerie();
		$DataSet->AddPoint($date_array,"XLabel");
		$DataSet->SetSerieName('Updates', 'Serie1');
		$DataSet->SetAbsciseLabelSerie("XLabel");
		$DataSet->SetYAxisName("Updates");

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('update_activity_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->setGraphArea(60,30,390,170);
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);
		$Test->drawRoundedRectangle(5,5,405,195,5,200,200,200);
		$Test->drawGraphArea(240,240,240);
		$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
		$Test->drawGrid(4,220,220,220);

		// Draw the 0 line
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',6);
		$Test->drawTreshold(0,143,55,72,TRUE,TRUE);

		// Draw the line graph
		$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
		$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

		// Finish the graph
		$Cache->WriteToCache('update_activity_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
	}

	/**
	 * Draws a pie chart of project activity for user $uid
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function projects_activity($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$update_model = new Update_Model;

		// Calculate the information needed in the graph.
		$projects = $project_model->projects($uid);

		$project_kudos_list = array();
		$project_name_list = array();
		$total = 0;

		foreach ($projects as $pid => $p_name)
		{
			$update_number = $update_model->project_updates($pid, $uid);
			$project_update_list[] = $update_number;
			$project_name_list[] = $p_name .' ('. $update_number .')';
			$total = $total + $update_number;
		}

		if ($total == 0)
		{
			$handle = ImageCreate(1,1) or die('fail');
			header("content-type: image/jpeg");
			Imagejpeg($handle);
			die();
		}

		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($project_update_list, 'Serie1');
		$DataSet->AddPoint($project_name_list, 'Serie2');
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie('Serie2');

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('projects_activity_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);  
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);  
		$Test->drawRoundedRectangle(5,5,405,195,5,230,230,230); 

		// Draw the pie chart
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),130,90,100,PIE_PERCENTAGE,TRUE,50,20,5);  
		$Test->drawPieLegend(265,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250); 

		$Cache->WriteToCache('projects_activity_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
	}


	/**
	 * Draws a pie chart of popular projects based on kudos for user $uid
	 *
	 * @param int $uid The uid to draw for.
	 *
	 * @return null
	 */
	public function popular_project_kudos($uid)
	{
		// Load necessary models.
		$project_model = new Project_Model;
		$kudos_model = new Kudos_Model;

		// Calculate the information needed in the graph.
		$projects = $project_model->projects($uid);

		$project_kudos_list = array();
		$project_name_list = array();

		foreach ($projects as $pid => $p_name)
		{
			$kudos_number = $kudos_model->kudos_project($pid, $uid);
			$project_kudos_list[] = $kudos_number;
			$project_name_list[] = $p_name .' ('. $kudos_number .')';
		}

		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($project_kudos_list, 'Serie1');
		$DataSet->AddPoint($project_name_list, 'Serie2');
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie('Serie2');

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('popular_project_kudos_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);  
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);  
		$Test->drawRoundedRectangle(5,5,405,195,5,230,230,230); 

		// Draw the pie chart
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),130,90,100,PIE_PERCENTAGE,TRUE,50,20,5);  
		$Test->drawPieLegend(265,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250); 

		$Cache->WriteToCache('popular_project_kudos_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
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
		$track_model = new Track_Model;

		// Calculate the information needed in the graph.
		$projects = $project_model->projects($uid);

		// We will not track the "Uncategorised" project as it is special.
		//unset($projects[1]);

		$project_subscribe_list = array();
		$project_name_list = array();
		$total = 0;

		foreach ($projects as $pid => $p_name)
		{
			if ($pid == 1)
			{
				// Because you cannot subscribe to uncategorised projects, this 
				// will instead show the number of people tracking you.
				$subscribed_number = $track_model->track($uid);
				$project_subscribe_list[] = $subscribed_number;
				$project_name_list[] = 'Trackers ('. $subscribed_number .')';
			}
			else
			{
				$subscribed_number = $subscribe_model->subscribe($pid);
				$project_subscribe_list[] = $subscribed_number;
				$project_name_list[] = $p_name .' ('. $subscribed_number .')';
			}
			$total = $total + $subscribed_number;
		}

		if ($total == 0)
		{
			$handle = ImageCreate(1,1) or die('fail');
			header("content-type: image/jpeg");
			Imagejpeg($handle);
			die();
		}

		// ... require needed files for graph generation.
		require Kohana::find_file('vendor', 'pchart/pChart/pData', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pChart', $required = TRUE, $ext = 'class');
		require Kohana::find_file('vendor', 'pchart/pChart/pCache', $required = TRUE, $ext = 'class');

		// Dataset definition
		$DataSet = new pData;
		$DataSet->AddPoint($project_subscribe_list, 'Serie1');
		$DataSet->AddPoint($project_name_list, 'Serie2');
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie('Serie2');

		// Cache definition
		$Cache = new pCache();
		$Cache->GetFromCache('popular_project_subscribers_'. $uid, $DataSet->GetData());

		// Initialise the graph
		$Test = new pChart(410,200);  
		$Test->drawFilledRoundedRectangle(7,7,403,193,5,240,240,240);  
		$Test->drawRoundedRectangle(5,5,405,195,5,230,230,230); 

		// Draw the pie chart
		$Test->setFontProperties(DOCROOT.'application/vendor/pchart/Fonts/tahoma.ttf',8);
		$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),130,90,100,PIE_PERCENTAGE,TRUE,50,20,5);  
		$Test->drawPieLegend(265,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250); 

		$Cache->WriteToCache('popular_project_subscribers_'. $uid, $DataSet->GetData(), $Test);
		$Test->Stroke();
	}
}
