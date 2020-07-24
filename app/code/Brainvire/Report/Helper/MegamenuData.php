<?php 

namespace Brainvire\Report\Helper;

class MegamenuData extends \Ves\Megamenu\Helper\Data
{
	public function drawItem($item, $level = 0, $x = 0, $listTag = true){
		$hasChildren = false;
		$generate_mobile_menu_code = $this->getConfigMobileMenu();
		$class = $style = $attr = '';
		if(isset($item['class'])) $class = $item['class'];
		if(!isset($item['status']) || (isset($item['status']) && !$item['status'])) return;
		if(isset($item['children']) && count($item['children'])>0) $hasChildren = true;

		$class .= ' nav-item level' . $level . ' nav-' . $x;
		// Item Align Type
		if($item['align'] == '1'){
			$class .= ' submenu-left';
		}elseif($item['align'] == '2'){
			$class .= ' submenu-right';
		}elseif($item['align'] == '3'){
			$class .= ' submenu-alignleft';
		}elseif($item['align'] == '4'){
			$class .= ' submenu-alignright';
		}

		// Group Childs Item
		if($item['is_group']){
			$class .= ' subgroup ';
		}else{
			$class .= ' subhover ';
		}

		if($item['content_type'] == 'dynamic') $class .= ' subdynamic';

		// Disable Dimesion
		if(((int)$item['disable_bellow'])>0)
			$attr .= 'data-disable-bellow="'.$item['disable_bellow'].'"';
		
		if($level==0){
			$class .=' dropdown level-top';
		}else{
			$class .=' dropdown-submenu';
		}
		$class .= ' '.$item['classes'];

		// Custom Link, Category Link
		$href = '';
		$onclick = '';
		if($item['link_type'] == 'custom_link'){
			$href = $item['link'];

			if((strpos($href, ":javascript:") !== false) || (strpos($href, ":JAVASCRIPT:") !== false)){
				$href = '#';
				$onclick = str_replace(array(":javascript:", ":JAVASCRIPT:"), "", $href);
				$onclick = str_replace('"', "'", $onclick);
				$onclick = trim($onclick);
			}
		}elseif($item['link_type'] == 'category_link'){
			if ($category = $this->getCategory($item['category'])) {
				$href = $category['url'];
				if($urls = parse_url($href)){
					$url_host = isset($urls['host'])?$urls['host']:"";
					$base_url = $this->_storeManager->getStore()->getBaseUrl();
					if($url_host && ($base_urls = parse_url($base_url))) {
						$base_urls['host'] = isset($base_urls['host'])?$base_urls['host']:"";
						if($url_host != $base_urls['host']){
							$href = str_replace($url_host, $base_urls['host'], $href);
						}
					}
				}
			}
		}

		$link = $this->filter($href);
		$link = trim($link);
		if($this->endsWith($link, '/')){
			$link = substr_replace($link, "", -1);
		}
		$currentUrl = trim($this->_url);
		$currentUrl = $this->filter($currentUrl);
		if($this->endsWith($currentUrl, '/')){
			$currentUrl = substr_replace($currentUrl, "", -1);
		}
		if($link == $currentUrl && ($href != '' && $href!='#')){
			$class .= ' active';
		}

		if($listTag){
			if($class!='') $class = 'class="' . $class . '"';
			$html = '<li id=' . $item['htmlId'] . ' ' . $class . ' ' . $style . ' ' . $attr . '>';
		}else{
			if(isset($item['dynamic'])){
				$class .= ' dynamic-item '.$item['htmlId'];
			}
			if($class!='') $class = 'class="' . $class . '"';
			$html = '<div ' . $class . ' ' . $style . ' ' . $attr . '>';
		}

		if(!isset($item['dynamic'])) $html .= $this->drawAnchor($item);
		$tChildren = false;
		$catChildren = [];
		if($item['content_type'] == 'parentcat'){
			$catChildren = $this->getTreeCategories($item['parentcat']);
			if($catChildren) $tChildren = true;
		}
		if(($item['show_footer'] && $item['footer_html']!='') || ($item['show_header'] && $item['header_html']!='') ||  ($item['show_left_sidebar'] && $item['left_sidebar_html']!='') || ($item['show_right_sidebar'] && $item['right_sidebar_html']!='') || ($item['show_content'] && ((($item['content_type'] == 'childmenu' || $item['content_type'] == 'dynamic') && (isset($item['children']) && count($item['children'])>0)) || ($item['content_type'] == 'content' && $item['content_html']!=''))) || ($item['content_type'] == 'parentcat' && $tChildren) ){
			$level++;
			$subClass = $subStyle = $subAttr = '';

			if($item['sub_width']!='') $subStyle .= 'width:'.$item['sub_width'].';';

			if(isset($item['dropdown_bgcolor']) && $item['dropdown_bgcolor']) $subStyle .= 'background-color:'.$item['dropdown_bgcolor'].';';
			if(isset($item['dropdown_bgimage']) && $item['dropdown_bgimage']){
				if(!$item['dropdown_bgpositionx']) $item['dropdown_bgpositionx'] = 'center';
				if(!$item['dropdown_bgpositiony']) $item['dropdown_bgpositiony'] = 'center';
				$subStyle .= 'background: url(\''.$item['dropdown_bgimage'].'\') ' . $item['dropdown_bgimagerepeat'] . ' ' . $item['dropdown_bgpositionx'] . ' ' . $item['dropdown_bgpositiony'] . ' ' . $item['dropdown_bgcolor'] . ';' ;
			}
			if(isset($item['dropdown_inlinecss']) && $item['dropdown_inlinecss']) $subStyle .= $item['dropdown_inlinecss'];
			
			$subClass .= 'submenu';
			
			if(isset($item['animation_in'])){
				$subClass .= ' animated ';
				$subClass .= $item['animation_in'];
				if($item['animation_in']){
					$subAttr .= ' data-animation-in="' . $item['animation_in'] . '"';
				}
				if($item['animation_time']){
					$subStyle .= 'animation-duration: ' . $item['animation_time'] . 's;-webkit-animation-duration: ' . $item['animation_time'] . 's;';
				}
			}

			if($item['is_group']){
				$subClass .= ' dropdown-mega';
			}else{
				$subClass .= ' dropdown-menu';
			}
			if($subClass!='') $subClass = 'class="' . $subClass . '"';
			if($subStyle!='') $subStyle = 'style="' . $subStyle . '"';

			if(!isset($item['dynamic']))
				$html .= '<div ' . $subClass . ' ' . $subStyle . '>';
		
			
			// TOP BLOCK
			if($item['show_header'] && $item['header_html']!=''){
				$html .= '<div class="megamenu-header">' . $this->decodeWidgets($item['header_html']) . '</div>';
			}

			if($item['show_left_sidebar'] || $item['show_content'] || $item['show_right_sidebar']){
				if(isset($item['dynamic']) && $item['dynamic']) {
					$html .= '<div class="content-wrap"'.' ' . $subStyle .'>';
				} else {
					$html .= '<div class="content-wrap">';
				}
				

				$left_sidebar_width = isset($item['left_sidebar_width'])?$item['left_sidebar_width']:0;
				$content_width = $item['content_width'];
				$right_sidebar_width = isset($item['right_sidebar_width'])?$item['right_sidebar_width']:0;

				// LEFT SIDEBAR BLOCK
				if($item['show_left_sidebar'] && $item['left_sidebar_html']!=''){
					if($left_sidebar_width) $left_sidebar_width = 'style="width:'.$left_sidebar_width.'"';
					
					$html .= '<div class="megamenu-sidebar left-sidebar" '.$left_sidebar_width.'>'.$this->decodeWidgets($item['left_sidebar_html']).'</div>';
				}
				// MAIN CONTENT BLOCK
				if($item['show_content'] && ((($item['content_type'] == 'childmenu' || $item['content_type'] == 'dynamic') && $hasChildren) || $item['content_type'] == 'parentcat' || ($item['content_type'] == 'content' && $item['content_html']!=''))){
					$html .= '<div class="megamenu-content" '.($content_width==''?'':'style="width:'.$content_width.'"').'>';

					// Content HTML
					if($item['content_type'] == 'content' && $item['content_html']!=''){
						$html .= '<div class="nav-dropdown">' . $this->decodeWidgets($item['content_html']) . '</div>';
					}

					// Dynamic Tab
					if($item['content_type'] == 'dynamic' && $hasChildren){
						$html .= '<div class="level' . $level . ' nav-dropdown">';
						$children = $item['children'];
						$i = 0;
						$total = count($children);
						$column = (int)$item['child_col'];
						$z = 0;
						$html .= '<div class="dorgin-items row hidden-sm hidden-xs">';
						$html .= '<div class="dynamic-items col-xs-3 hidden-xs hidden-sm">';
						$html .= '<ul>';
						foreach ($children as $it) {
							$iClass = '';
							if($z==0){
								$iClass = 'class="dynamic-active"';
							}
							$html .= '<li ' . $iClass . ' data-dynamic-id="' . $it['htmlId'] . '">';
							$html .= $this->drawAnchor($it, $level);
							$html .= '</li>';
							$i++;
							$z++;
						}
						$html .= '</ul>';
						$html .= '</div>';
						$html .= '<div class="dynamic-content col-xs-9 hidden-xs hidden-sm">';
						$z = 0;
						foreach ($children as $it) {
							if($z==0){ $it['class'] = 'dynamic-active'; }
							$it['dynamic'] = true;
							$html .= $this->filter($this->drawItem($it, $level, $i, false));
							$i++;
							$z++;
						}
						$html .= '</div>';
						$html .= '</div>';

						$html .= '<div class="orgin-items hidden-lg hidden-md">';
						$i = 0;
						$column = 1;
						foreach ($children as $it) {
							if( $column == 1 || $i%$column == 0){
								$html .= '<div class="row">';
							}
							$html .= '<div class="mega-col col-sm-' . (12/$column) . ' mega-col-' . $i . ' mega-col-level-' . $level . '">';
							$html .= $this->filter($this->drawItem($it, $level, $i, false));
							$html .= '</div>';
							if( $column == 1 || ($i+1)%$column == 0 || $i == ($total-1) ) {
								$html .= '</div>';
							}
							$i++;
						}
						$html .= '</div>';


						$html .= '</div>';
					}

					// Child item
					if ($item['content_type'] == 'childmenu' && $hasChildren) {
						$column = (int)$item['child_col'];
						$tablet_column = isset($item['tablet_child_col'])?(int)$item['tablet_child_col']:$column;
						$mobile_column = isset($item['mobile_child_col'])?(int)$item['mobile_child_col']:1;
						$html .= '<div class="level' . $level . ' nav-dropdown ves-column' . $column . ' ves-column-tablet' . $column . ' ves-column-mobile' . $mobile_column . '">';
						$children = $item['children'];
						$i = 0;
						$total = count($children);
						$isEnable = $this->scopeConfig->getValue(
                        'error_report/general/enable',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                        if($isEnable != 1){
                        	$pos = array_search('Report an Error', array_column($children, 'name'));
                        	unset($children[$pos]);
                        }
						$resultTmp = [];
						$x1 = 0;
						$levelTmp =1;
						
						foreach ($children as  $z => $it) {
							$resultTmp[$x1][$levelTmp] = $this->drawItem($it, $level, $i, false);
							if ($x1==$column-1 || $i == (count($children)-1)) {
								$levelTmp++;
								$x1=0;
							} else {
								$x1++;
							}
							$i++;
						}
						if($generate_mobile_menu_code){
							$html .= '<div class="item-content1 '.self::$_hidden_menu_content_1.'">';
						}else{
							$html .= '<div class="item-content1">';
						}
						foreach ($resultTmp as $k1 => $v1) {
							$html .= '<div class="mega-col mega-col-' . $i . ' mega-col-level-' . $level . '">';
							foreach ($v1 as $k2 => $v2) {
								$html .= $v2;
							}
							$html .= '</div>';
						}
						$html .= '</div>';
						if($generate_mobile_menu_code){
							$html .= '<div class="item-content2 '.self::$_hidden_menu_content_2.'">';
							foreach ($children as  $z => $it) {
								$html .= $this->filter($this->drawItem($it, $level, $i, false));
							}
							$html .= '</div>';
						}
						$html .= '</div>';
					}
					
					// Child item
					if($item['content_type'] == 'parentcat'){
						$html .= '<div class="level' . $level . ' nav-dropdown">';
						$catChildren = $this->getTreeCategories($item['parentcat']);

						$i = 0;
						$total = count($catChildren);
						$column = (int)$item['child_col'];
						$tablet_column = isset($item['tablet_child_col'])?(int)$item['tablet_child_col']:$column;
						$tablet_column = $tablet_column?$tablet_column:$column;
						$mobile_column = isset($item['mobile_child_col'])?(int)$item['mobile_child_col']:1;
						$mobile_column = $mobile_column?$mobile_column:1;
						foreach ($catChildren as $it) {
							if( $column == 1 || $i%$column == 0){
								$html .= '<div class="row">';
							}
							$html .= '<div class="mega-col col-xl-' . (12/$column) . ' col-sm-' . (12/$tablet_column) . ' mega-col-' . $i . ' mega-col-level-' . $level . ' col-xs-'.(12/$mobile_column).'">';
							$html .= $this->drawItem($it, $level, $i, false);
							$html .= '</div>';
							if( $column == 1 || ($i+1)%$column == 0 || $i == ($total-1) ) {
								$html .= '</div>';
							}
							$i++;
						}
						$html .= '</div>';

					}
					$html .= '</div>';
				}

				// RIGHT SIDEBAR BLOCK
				if($item['show_right_sidebar'] && $item['right_sidebar_html']!=''){
					if($right_sidebar_width) $right_sidebar_width = 'style="width:' . $right_sidebar_width . '"';
					$html .= '<div class="megamenu-sidebar right-sidebar" '.$right_sidebar_width.'>'.$this->decodeWidgets($item['right_sidebar_html']).'</div>';
				}

				$html .= '</div>';
			}

			// BOOTM BLOCK
			if( $item['show_footer'] && $item['footer_html']!=''){
				$html .= '<div class="megamenu-footer">'.$this->decodeWidgets($item['footer_html']).'</div>';
			}

			if(!isset($item['dynamic']))
				$html .= '</div>';
		}
		if($listTag){
			$html .= '</li>';
		}else{
			$html .= '</div>';	
		}
		$html= $this->decodeImg($html);
		return $html;
	}
}