<?php
	$rootPath = $_SERVER['DOCUMENT_ROOT'];
	$config = json_decode(file_get_contents($rootPath . 'config.json'), true);
	$dirContent = scandir($rootPath);
	$dirContent = array_slice($dirContent, 2, count($dirContent));
	$allProjectsContent = '';
	$favoriteProjectsContent = '';

	foreach($dirContent as $content){
		$activeClass = in_array($content, $config['favorite']) ? ' active' : '';
		if(is_dir($rootPath . $content)){
			$allProjectsContent .= getProjectHtml($rootPath, $content, $activeClass);
		}
	}
	foreach($config['favorite'] as $content){
		$favoriteProjectsContent .= getProjectHtml($rootPath, $content);
	}

	function getProjectHtml($rootPath, $content, $activeClass = null){
		$favoriteHtml = $branchHtml = '';
		$branch = getBranch($rootPath, $content);
		if(isset($activeClass)){
			$favoriteHtml = '<a href="/' . $content . '" class="add-favorite' . $activeClass . '"></a>';
		}
		if(isset($branch)){
			$branchHtml = '<span class="branch">' . $branch . '</span>';
		}
		return '<li>'
					. $favoriteHtml .
					'<a href="/' . $content . '">' . $content . '</a>
						<span>' . ago(filemtime($rootPath . $content . '.')) . '</span>'
						. $branchHtml .
				'</li>';
	}

	function getBranch($rootPath, $content){
		$gitFile = $rootPath . $content . '/.git/HEAD';
		if(is_file($gitFile)){
			return str_replace('ref: refs/heads/', '', file_get_contents($gitFile));
		}
	}

	function ago($timestamp){
		$difference = time() - $timestamp;
		$periods = array("second", "minute", "hour", "day", "week", "month", "years", "decade");
		$lengths = array("60", "60", "24", "7", "4.35", "12", "10");
		for($j = 0; $difference >= $lengths[$j]; $j++){
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
		if($difference != 1){
			$periods[$j] .= "s";
		}
		$text = "$difference $periods[$j] ago";
		return $text;
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $_SERVER['SERVER_ADDR']; ?></title>
		<style type="text/css">
			body{
				font-family: Helvetica, Arial;
				color: #333333;
				padding: 5px 30px;
			}
			ul{
				list-style: none;
				margin: 0;
				padding: 0;
			}
			a{
				color: rgb(0, 0, 238);
				text-decoration: none;
				display: block;
				padding: 5px 0;
				font-weight: bold;
				-webkit-box-sizing: border-box;
				box-sizing: border-box;
				position: relative;
			}
			a:hover::after{
				content: '';
				display: block;
				border-bottom: 1px solid rgba(51, 51, 51, .3);
				width: 100%;
				position: absolute;
				bottom: 0;
				left: 0;
			}
			.projects{
				display: -webkit-box;
				display: box;
				-webkit-box-pack: center;
				box-pack: center;
			}
			.projects > div{
				width: 50%;
				-webkit-box-sizing: border-box;
				box-sizing: border-box;
				padding: 0 10px 0 0;
			}
			h2{
				border-bottom: 1px solid #333333;
				margin: 0;
			}
			ul li{
				position: relative;
				margin: 8px 0;
			}
			ul li span{
				position: absolute;
				bottom: 0;
				right: 0;
				font-size: 11px;
				color: #333333;
			}
			ul li span.branch{
				bottom: auto;
				top: 0;
				background: #eee;
				border: 1px solid #ccc;
				border-radius: 3px;
				padding: 0 3px;
			}
			a.add-favorite{
				position: absolute;
				top: 0;
				left: -35px;
				width: 30px;
				height: 29px;
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAA8CAYAAABxVAqfAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkE0NEM3RkE3OTQ5RjExRTI4QkY2QTAyMTI1N0E1RTQxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkE0NEM3RkE4OTQ5RjExRTI4QkY2QTAyMTI1N0E1RTQxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QTQ0QzdGQTU5NDlGMTFFMjhCRjZBMDIxMjU3QTVFNDEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QTQ0QzdGQTY5NDlGMTFFMjhCRjZBMDIxMjU3QTVFNDEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5mvgfnAAAHgElEQVR42rxZd2wbVRj/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) no-repeat;
				cursor: pointer;
			}
			a.add-favorite:hover::after{
				display: none;
			}
			a.add-favorite.active{
				background-position-y: 100%;
				border: none;
			}
		</style>
		<script type="text/javascript">
			window.addEventListener('load', function(){
				var favoriteClassName = 'add-favorite', i,
					favoriteButtons = document.getElementsByClassName(favoriteClassName),
					favoriteList = document.getElementsByClassName('favorite-list')[0];
				for(i = 0; i < favoriteButtons.length; i++){
					favoriteButtons[i].addEventListener('click', function(event){
						var href = this.getAttribute('href').replace('/', ''),
							xhr = new XMLHttpRequest(),
							that = this;
						event.stopPropagation();
						event.preventDefault();
						xhr.onreadystatechange = function(){
							var response, favoriteProject, projectLink, setClassName = favoriteClassName;
							if(this.readyState === 4){
								response = JSON.parse(this.responseText);
								if(response.isSet){
									setClassName += ' active';
									favoriteProject = that.parentNode.cloneNode(true);
									favoriteProject.removeChild(favoriteProject.firstChild);
									if(favoriteList.firstChild){
										favoriteList.insertBefore(favoriteProject, favoriteList.firstChild);
									}else{
										favoriteList.appendChild(favoriteProject);
									}
								}else{
									favoriteList.removeChild(favoriteList.children[response.contentIndex]);
								}
								that.className = setClassName;
							}
						};
						xhr.open('GET', 'favorite.php?href=' + href, true);
						xhr.send(null);
					});
				}
			});
		</script>
	</head>
	<body>
		<div class="projects">
			<div class="all">
				<h2>Projects</h2>
				<ul><?php echo $allProjectsContent; ?></ul>
			</div>
			<div class="favorite">
				<h2>Favorite</h2>
				<ul class="favorite-list"><?php echo $favoriteProjectsContent; ?></ul>
			</div>
		</div>
	</body>
</html>