<?php

/**
 *
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// lib theme register blocks
function custom_acf_register_block_type() {

	// $path = dirname(__FILE__) . '/app/blocks';
	$blocks_paths = [
		'app/blocks'
	];
	foreach ($blocks_paths as $key_path => $blocks_path) {
		$path = get_stylesheet_directory() . '/' . $blocks_path;
		if (file_exists($path)) {
			foreach (new \DirectoryIterator($path) as $file) {
				if ($file->isDot())
					continue;

				if ($file->isDir()) {
					$dir = $path . '/' . $file->getFilename();
					$files = scandir($dir);

					foreach ($files as $filename) {

						if ($filename === '.' or $filename === '..')
							continue;

						$path_info_folder = pathinfo($filename);
						if (!isset($path_info_folder['extension'])) {
							continue;
						}

						// handle is same as folder name
						$foldername = $file->getFilename();

						// file inside the folder
						// file inside the folder
						$asset_url_Path = get_stylesheet_directory_uri() . '/' . $blocks_path . '/' . $foldername . '/' . $filename;
						$asset_dir_Path = get_stylesheet_directory() . '/' . $blocks_path . '/' . $foldername . '/' . $filename;

						// register block if json file found
						if ($path_info_folder['extension'] == 'json' && $filename) {
							register_block_type($dir . '/' . $path_info_folder['basename']);
						}

						//register css file with handle id same as its folder name
						if ($path_info_folder['extension'] == 'css' && $filename) {

							if ($filename == 'style.css') {
								wp_register_style($foldername.'-style', $asset_url_Path);
							}

							if ($filename == 'editor.css') {
								wp_register_style("{$foldername}-editor-style", $asset_url_Path);
							}
						}

						//register css and scss file with handle id same as its folder name
						if ($path_info_folder['extension'] == 'scss' && $filename) {
							if ($filename ==  $foldername . '.scss') {
								wp_register_style(
									$foldername . "-css",
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '.scss'),
									[],
									null
								);
							}

							if ($filename == $foldername . '-front.scss') {
								wp_register_style(
									$foldername . "-front",
									// $asset_url_Path,
									// array(),
									// filemtime($asset_dir_Path)
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '-front.scss'),
									[],
									null
								);
							}

							if ($filename == $foldername . '-editor.scss') {
								wp_register_style(
									"{$foldername}-editor",
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '-editor.scss'),
									[],
									null
								);
							}
						}

						// register js file with handle id same as its folden name
						if ($path_info_folder['extension'] == 'js' && $filename) {

							if ($filename == $foldername . '.js') {
								wp_register_script(
									"{$foldername}",
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '.js'),
									array('jquery'),
									filemtime($asset_dir_Path),
									array(
										'in_footer' => true,
										'strategy' => 'defer',
									)
								);
							}

							if ($filename == $foldername . '-front.js') {
								wp_register_script(
									"{$foldername}-front",
									// $asset_url_Path,
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '-front.js'),
									array('jquery'),
									filemtime($asset_dir_Path),
									array(
										'in_footer' => true,
										'strategy' => 'defer',
									)
								);
							}

							if ($filename == $foldername . '-editor.js') {
								wp_register_script(
									"{$foldername}-editor",
									Vite::asset($blocks_path . '/' . $foldername . '/' . $foldername . '-editor.js'),
									array('jquery'),
									filemtime($asset_dir_Path),
									array(
										'in_footer' => true,
										'strategy' => 'defer'
									)
								);
							}
						}
					}
				}
			}
		}
	}
}
add_action('init', 'custom_acf_register_block_type');

// function seap_register_acf_blocks() {

// 	$path = dirname(__FILE__) . '/app/blocks';

// 	foreach (new \DirectoryIterator($path) as $file) {
// 		if ($file->isDot())
// 			continue;

// 		if ($file->isDir()) {
// 			$dir = $path . '/' . $file->getFilename();
// 			$files = scandir($dir);

// 			foreach ($files as $filename) {

// 				if ($filename === '.' or $filename === '..')
// 					continue;

// 				$path_info_folder = pathinfo($filename);

// 				// handle is same as folder name
// 				$foldername = $file->getFilename();

// 				// file inside the folder
// 				$assetPath = get_template_directory_uri() . '/app/blocks/' . $foldername . '/' . $filename;

// 				// register block if json file found
// 				if ($path_info_folder['extension'] == 'json' && $filename) {
// 					register_block_type($dir . '/' . $path_info_folder['basename']);
// 				}

// 				//register css file with handle id same as its folder name
// 				if ($path_info_folder['extension'] == 'css' && $filename) {

// 					if ($filename == 'style.css') {
// 						wp_register_style($foldername, $assetPath);
// 					}

// 					if ($filename == 'editor.css') {
// 						wp_register_style("{$foldername}-editor", $assetPath);
// 					}
// 				}

// 				// register js file with handle id same as its folden name
// 				if ($path_info_folder['extension'] == 'js' && $filename) {

// 					if ($filename == 'view.js') {
// 						wp_register_script(
// 							"{$foldername}-front",
// 							$assetPath,
// 							array(),
// 							THEME_VERSION,
// 							array(
// 								'in_footer' => true,
// 								'strategy' => 'defer',
// 							)
// 						);
// 					}

// 					if ($filename == 'editor.js') {
// 						wp_register_script(
// 							"{$foldername}-editor",
// 							$assetPath,
// 							array(),
// 							THEME_VERSION,
// 							array(
// 								'in_footer' => true,
// 								'strategy' => 'defer'
// 							)
// 						);
// 					}
// 				}
// 			}
// 		}
// 	}
// }
// add_action('init', 'seap_register_acf_blocks', 12);




// block asset optimization









/**
 * https://developer.wordpress.org/reference/hooks/render_block/
 */
// add_filter('render_block', function ($block_content, $block) {
// 	$remove_class = [
// 		'wp-block-group',
// 		'is-nowrap', 'is-layout-flex', 'wp-container-core-group-is-layout-2', 'wp-block-group-is-layout-flex'
// 	];
// 	// Only affect this specific block
// 	if ('yipl/listcollection' === $block['blockName']) {
// 		$block_content = str_replace($remove_class, '', $block_content);
// 	}
// 	// Always return the content
// 	return $block_content;
// }, 10, 2);
