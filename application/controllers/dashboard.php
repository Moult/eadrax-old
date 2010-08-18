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
	public function index($offset = 0)
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

		// Create the statistics widget.
		$statistics_view = new View('statistics');

		// Calculate total number of kudos.
		$kudos_total = 0;

		$projects = $project_model->projects($this->uid);
		foreach ($projects as $pid => $p_name)
		{
			$kudos_number = $kudos_model->kudos_project($pid, $this->uid);

			// Add up the number of kudos
			$kudos_total = $kudos_total + $kudos_number;
		}

		// Set the number of kudos
		$statistics_view->total = $kudos_total;

		// Let's format data to generate charts for update activity over time.
		// Calculate the time ranges 5 weeks into the past.
		$week8_end = date("Y-m-d", strtotime("last Sunday"));
		$week8_start = date("Y-m-d", strtotime("last Sunday", strtotime($week8_end)));
		$week7_start = date("Y-m-d", strtotime("last Sunday", strtotime($week8_start)));
		$week6_start = date("Y-m-d", strtotime("last Sunday", strtotime($week7_start)));
		$week5_start = date("Y-m-d", strtotime("last Sunday", strtotime($week6_start)));
		$week4_start = date("Y-m-d", strtotime("last Sunday", strtotime($week5_start)));
		$week3_start = date("Y-m-d", strtotime("last Sunday", strtotime($week4_start)));
		$week2_start = date("Y-m-d", strtotime("last Sunday", strtotime($week3_start)));
		$week1_start = date("Y-m-d", strtotime("last Sunday", strtotime($week2_start)));

		// Calculate time ranges for each day 5 weeks into the past.
		$year_end = date("Y", strtotime("last Sunday"));
		$month_end = date("m", strtotime("last Sunday"));
		$day_end = date("d", strtotime("last Sunday"));

		$activity_list = array();
		$activity_list[] = $update_model->update_number_time($this->uid, $week1_start, $week2_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week2_start, $week3_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week3_start, $week4_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week4_start, $week5_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week5_start, $week6_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week6_start, $week7_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week7_start, $week8_start);
		$activity_list[] = $update_model->update_number_time($this->uid, $week8_start, $week8_end);

		$view_list = array();
		for ($i = 49; $i >= 0; $i--) {
			$view_list[] = $update_model->view_number_time($this->uid, date('Y-m-d', mktime(0, 0, 0, $month_end, $day_end-$i-1, $year_end)), date('Y-m-d', mktime(0, 0, 0, $month_end, $day_end-$i, $year_end))) + $project_model->view_number_time($this->uid, date('Y-m-d', mktime(0, 0, 0, $month_end, $day_end-$i-1, $year_end)), date('Y-m-d', mktime(0, 0, 0, $month_end, $day_end-$i, $year_end)));
		}

		// Reformat the dates to show in the graph nicely.
		$date_array = array(
			date("jS", strtotime($week2_start)),
			date("jS", strtotime($week3_start)),
			date("jS", strtotime($week4_start)),
			date("jS", strtotime($week5_start)),
			date("jS", strtotime($week6_start)),
			date("jS", strtotime($week7_start)),
			date("jS", strtotime($week8_start)),
			date("jS", strtotime($week8_end))
		);

		$month_array = array(
			date("M", strtotime($week2_start)),
			date("M", strtotime($week3_start)),
			date("M", strtotime($week4_start)),
			date("M", strtotime($week5_start)),
			date("M", strtotime($week6_start)),
			date("M", strtotime($week7_start)),
			date("M", strtotime($week8_start)),
			date("M", strtotime($week8_end))
		);

		// We want to clear out consecutive months in our axis.
		for ($i = 7; $i > 0; $i--) {
			if ($month_array[$i] == $month_array[$i-1]) {
				$month_array[$i] = '';
			}
		}

		// If there are insufficient data to show interesting charts ...
		if (max($view_list) == 0) { $statistics_view->nostats = 1; }

		// We need to calculate the peak values to use.
		$activity_peak = max($activity_list);
		$activity_peak = num::round($activity_peak)+5;
		$view_peak = max($view_list);
		$view_peak = num::round($view_peak)+5;

		// Let's start creating our URL for the chart!
		$chd = 't:';
		foreach ($view_list as $value) {
			$chd .= $value .',';
		}
		$chd = substr($chd, 0, -1) .'|';
		foreach ($activity_list as $value) {
			$chd .= $value .',';
		}
		$chd = substr($chd, 0, -1);

		$chds = '0,'. $view_peak .',0,'. $activity_peak;
		$chxr = '1,0,'. $activity_peak .'|2,0,'. $view_peak;

		$chxl = '0:|';
		foreach($date_array as $value) {
			$chxl .= $value .'|';
		}
		$chxl .= '3:|';
		foreach($month_array as $value) {
			$chxl .= $value .'|';
		}
		$chxl = substr($chxl, 0, -1);

		// Set all calculated chart variables.
		$statistics_view->line_chd = $chd;
		$statistics_view->line_chds = $chds;
		$statistics_view->line_chxr = $chxr;
		$statistics_view->line_chxl = $chxl;

		// Let's start calculating values for the stacked bar chart.
		$project_kudos_list = array();
		$total_updates = 0;

		$project_subscribe_list = array();
		$project_name_list = array();

		foreach ($projects as $pid => $p_name)
		{
			$update_number = $update_model->project_updates($pid, $this->uid);
			$project_update_list[] = $update_number;

			$kudos_number = $kudos_model->kudos_project($pid, $this->uid);
			$project_kudos_list[] = $kudos_number;

			if ($pid == 1)
			{
				// Because you cannot subscribe to uncategorised projects, this 
				// will instead show the number of people tracking you.
				$tracked_number = $track_model->track($this->uid);
				$project_subscribe_list[] = $tracked_number;
			}
			else
			{
				$subscribed_number = $subscribe_model->subscribe($pid);
				$project_subscribe_list[] = $subscribed_number + $tracked_number;
			}

			$total_updates = $total_updates + $update_number;
			$project_name_list[] = $p_name;
		}

		// Calculate the peak values...
		$project_total_list = array();
		foreach ($project_name_list as $key => $value) {
			$project_total_list[] = $project_update_list[$key] + $project_kudos_list[$key] + $project_subscribe_list[$key];
		}

		// Sort into descending order:
		$tmp_total_list = $project_total_list;

		// Sorting the three components of the chart based on 
		// $project_total_list would yield unpredictable results if the total 
		// array contained several identical values (ie 1, 2, 3, 3, 4). We will 
		// therefore offset each by a negligible and unique value to fix this.
		for ($i = 0; array_key_exists($i, $tmp_total_list); $i++) {
			$tmp_total_list[$i] += $i * 0.001;
		}

		// Now we can safely sort the rest without ambiguity.
		$tmp = $tmp_total_list;
		array_multisort($tmp, $project_update_list);
		$tmp = $tmp_total_list;
		array_multisort($tmp, $project_kudos_list);
		$tmp = $tmp_total_list;
		array_multisort($tmp, $project_subscribe_list);
		$tmp = $tmp_total_list;
		array_multisort($tmp, $project_name_list);

		$project_update_list = array_reverse($project_update_list);
		$project_kudos_list = array_reverse($project_kudos_list);
		$project_subscribe_list = array_reverse($project_subscribe_list);
		$project_name_list = array_reverse($project_name_list);


		$project_total_peak = max($project_total_list);
		$project_total_peak = ceil(intval($project_total_peak)/5)*5;

		$chxr = '1,0,'. $project_total_peak;
		$chds = '0,'. $project_total_peak;

		// Calculate the width of each bar.
		$project_number = count($project_name_list);
		$bar_width = ceil((360/$project_number)-10);
		$chbh = $bar_width .',10,15';

		// Set the values for the bar chart.
		$chd = 't:';
		foreach ($project_update_list as $value) {
			$chd .= $value .',';
		}
		$chd = substr($chd, 0, -1) .'|';
		foreach ($project_kudos_list as $value) {
			$chd .= $value .',';
		}
		$chd = substr($chd, 0, -1) .'|';
		foreach ($project_subscribe_list as $value) {
			$chd .= $value .',';
		}
		$chd = substr($chd, 0, -1);

		// How many characters will we allow before overflowing?
		$max_chars = array(
			1 => 47,
			2 => 27,
			3 => 22,
			4 => 17,
			5 => 13,
			6 => 9,
			7 => 7,
			8 => 5
		);

		// Set the values for the x-axis.
		$chxl = '0:|';
		$chxl2 = '2:|';
		$xline1 = array();
		$xline2 = array();
		foreach ($project_name_list as $value) {
			// ... if there isn't enough space to show the name ...
			if (strlen($value) > $max_chars[$project_number]) {
				// .. continue the name on the second x-axis (chxl2)
				$chxl .= ' |';
				$chxl2 .= '|';
				$xline1[] = $value;
				$xline2[] = TRUE;
			} else {
				$chxl .= ' |';
				$chxl2 .= '|';
				$xline1[] = $value;
				$xline2[] = FALSE;
			}
		}
		$chxl = $chxl . $chxl2;
		$chxl = substr($chxl, 0, -1);

		$statistics_view->bar_width = $bar_width;
		$statistics_view->bar_height = $project_total_peak;
		$statistics_view->bar_xline1 = $xline1;
		$statistics_view->bar_xline2 = $xline2;
		$statistics_view->bar_chbh = $chbh;
		$statistics_view->bar_chd = $chd;
		$statistics_view->bar_chxl = $chxl;
		$statistics_view->bar_chxr = $chxr;
		$statistics_view->bar_chds = $chds;

		// Create news "newsfeed" widget.
		$dashboard_newsfeed_view = new View('dashboard_newsfeed');

		// Prepare the newsfeed.
		$newsfeed = $update_model->news($this->uid, $offset);
		$news_view = array();

		// Pagination information.
		$dashboard_newsfeed_view->offset = $offset;
		$dashboard_newsfeed_view->news_total = $newsfeed[0];

		foreach($newsfeed[1] as $news)
		{
			// The logtime should be human readable.
			$logtime = date("jS F g:ia", strtotime($news->logtime));

			// The username and avatar is always useful!
			$user = $user_model->user_information($news->uid);
			$avatar = $user['avatar'];
			$user = $user['username'];

			if (!empty($news->cid))
			{
				$comment_information = $comment_model->comment_information($news->cid);
				$update_information = $update_model->update_information($news->upid);
				$picture = Updates_Controller::_file_icon($update_information['filename0'], $update_information['ext0']);
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has commented on the update <strong><a href="'. url::base() .'updates/view/'. $news->upid .'/" style="text-decoration: none;">'. $update_information['summary'] .'</a></strong>:',
					'picture' => $picture,
					'picture_url' => url::base() .'updates/view/'. $news->upid .'/',
					'comment_text' => $comment_information['comment']
				);
			}
			elseif (!empty($news->upid))
			{
				$update = $update_model->update_information($news->upid);
				$picture = Updates_Controller::_file_icon($update['filename0'], $update['ext0']);
				$update = $update['summary'];
				$project = $project_model->project_information($news->pid);
				$p_uid = $project['uid'];
				$project = $project['name'];
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has created a new update on the project <strong><a href="'. url::base() .'projects/view/'. $p_uid .'/'. $news->pid .'/" style="text-decoration: none;">'. $project .'</a></strong>',
					'picture' => $picture,
					'picture_url' => url::base() .'updates/view/'. $news->upid .'/',
					'update_text' => $update
				);
			}
			elseif (!empty($news->pid))
			{
				$project = $project_model->project_information($news->pid);
				if (!empty($project['icon'])) {
					$picture = url::base() .'uploads/icons/'. $project['icon'];
				} else {
					$picture = url::base() .'images/noprojecticon.png';
				}
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has created a new project! Round of applause, folks.',
					'picture' => $picture,
					'picture_url' => url::base() .'projects/view/'. $news->uid .'/'. $news->pid .'/',
					'update_text' => $project['name'],
					'extra_text' => $project['summary']
				);
			}
			elseif (!empty($news->kid))
			{
				$update = $update_model->update_information($news->kid);
				$picture = Updates_Controller::_file_icon($update['filename0'], $update['ext0']);
				$project = $project_model->project_information($update['pid']);
				$p_uid = $project['uid'];
				$project = $project['name'];
				$update_summary = $update['summary'];
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has kudos\'d an update on the project <strong><a href="'. url::base() .'projects/view/'. $p_uid .'/'. $news->pid .'/" style="text-decoration: none;">'. $project .'</a></strong>',
					'picture' => $picture,
					'picture_url' => url::base() .'updates/view/'. $update['id'] .'/',
					'update_text' => $update_summary
				);
			}
			elseif (!empty($news->sid))
			{
				$project = $project_model->project_information($news->sid);
				if (!empty($project['icon'])) {
					$picture = url::base() .'uploads/icons/'. $project['icon'];
				} else {
					$picture = url::base() .'images/noprojecticon.png';
				}
				$p_uname = $user_model->user_information($project['uid']);
				$p_uname = $p_uname['username'];
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has subscribed to a project by <strong><a href="'. url::base() .'profiles/view/'. $project['uid'] .'/" style="text-decoration: none;">'. $p_uname .'</a></strong>.',
					'picture' => $picture,
					'picture_url' => url::base() .'projects/view/'. $news->uid .'/'. $news->pid .'/',
					'update_text' => $project['name'],
					'extra_text' => $project['summary']
				);
			}
			elseif (!empty($news->tid))
			{
				$track_user = $user_model->user_information($news->tid);
				if (!empty($track_user['avatar'])) {
					$picture = url::base() .'uploads/avatars/'. $track_user['avatar'] .'_small.jpg';
				} else {
					$picture = url::base() .'images/noprojecticon.png';
				}
				$track_user = $track_user['username'];
				$news_view[] = array(
					'avatar' => $avatar,
					'logtime' => $logtime,
					'user' => $user,
					'uid' => $news->uid,
					'text' => 'has started tracking the user <strong><a href="'. url::base() .'profiles/view/'. $track_user .'/" style="text-decoration: none;">'. $track_user .'</a></strong>.',
					'picture' => $picture,
					'picture_url' => url::base() .'profiles/view/'. $track_user .'/',
					'update_text' => $track_user
				);
			}
		}

		$dashboard_newsfeed_view->newsfeed = $news_view;

		// Start finding trackers information.
		$track_uids = $track_model->track_list($this->uid);
		$track_list = array();

		// Track uids only contains uids, however usernames are useful for the 
		// view too!
		foreach ($track_uids as $uid)
		{
			$username = $user_model->user_information($uid);
			if (!empty($username['avatar'])) {
				$avatar = url::base() .'uploads/avatars/'. $username['avatar'] .'_small.jpg';
			} else {
				$avatar = url::base() .'images/noprojecticon.png';
			}
			$username = $username['username'];
			$track_list[] = array($uid, $username, $avatar);
		}

		$dashboard_newsfeed_view->track_total = $track_model->track($this->uid);
		$dashboard_newsfeed_view->track_list = $track_list;

		// Start finding subscribers information.
		$subscribed_total = 0;

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
					if (!empty($username['avatar'])) {
						$avatar = url::base() .'uploads/avatars/'. $username['avatar'] .'_small.jpg';
					} else {
						$avatar = url::base() .'images/noprojecticon.png';
					}
					$username = $username['username'];
					$subscribe_list[] = array($uid, $username, $avatar);
				}

				$subscribed_number = $subscribe_model->subscribe($pid);
				$project_subscribe_list[$pid] = array($p_name, $subscribed_number, $subscribe_list);

				// Add up the number of subscribers
				$subscribed_total = $subscribed_total + $subscribed_number;
			}
		}

		// Set all the information we gathered...
		$dashboard_newsfeed_view->project_subscribe_list = $project_subscribe_list;
		$dashboard_newsfeed_view->subscribe_total = $subscribed_total;

		// Generate the content.
		$this->template->content = array($statistics_view, $dashboard_newsfeed_view);
	}
}
