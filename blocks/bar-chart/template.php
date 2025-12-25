<?php

/**
 * =================================================
 * Support custom "anchor" values.
 * =================================================
 */
$id = '';
if (!empty($block['anchor'])) {
    $id = esc_attr($block['anchor']);
}

// use generated id if custom anchor is not provided
$id = $block['id'];

// Create class attribute allowing for custom "className" and "align" values.
$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}


/**
 * =================================================
 * Get acf field values
 * =================================================
 */

// title
$title = get_field('title');

// excerpt
$description = get_field('description');

//chart settings
$chartType = get_field('chart_type');
$horizontal = ($chartType === 'bar' && get_field('horizontal')) ? true : false;
$min_y = (get_field('y_min_value')) ?: '';
$max_y = (get_field('y_max_value')) ?: '';

// chart datas
$series = array();
$datas = array();
$labels = array();
$values = array();
$colors = array();

if (have_rows('dataset')):
    $i = 0;
    while (have_rows('dataset')):
        the_row();

        $label = get_sub_field('label');
        $data = get_sub_field('data');
        $color = (get_sub_field('color')['gutenberg_color_picker'] != 'custom') ? get_sub_field('color')['gutenberg_color_picker'] : get_sub_field('color')['bg_color'];

        // collect all labels of series
        $labels[$i] = $label;

        // collect all values of labels
        $values[$i] = (int) $data;

        // collect all colors of labels
        $colors[$i] = $color;

        $datas[$i] = (object) [
            'x' => $label,
            'y' => $data,
            'fillColor' => $color,
        ];

        $i++;

    endwhile;

endif;

/**
 * =================================================
 * Generated chart script for frontend
 * =================================================
 */
switch ($chartType) {
    case 'bar':
        $series = [];
        $series[0] = (object) [
            'name' => '',
            'data' => $datas
        ];
        $generateChartData = [
            'series' => $series,
            'chart' => [
                'height' => 350,
                'type' => $chartType,

            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 0,
                    'horizontal' => $horizontal,
                ],

            ],
            'dataLabels' => [
                'enabled' => true,
                // 'style' => [
                // 'colors' => ['var(--n-10)'],

                // ],

            ],

            'grid' => [
                'show' => false,
            ],
            'xaxis' => [
                'labels' => [
                    'show' => true,
                ],
                'axisBorder' => [
                    'show' => false,
                ]
            ]
        ];

        $chartOptions = json_encode($generateChartData);

        $options = "
                const options_{$id} = $chartOptions;
            ";
        break;
    case 'pie':
        $generateChartData = [
            'series' => $values,
            'chart' => [
                'height' => 350,
                'type' => $chartType
            ],
            'colors' => $colors,
            'labels' => $labels,
            'legend' => [
                'position' => 'bottom'
            ],
        ];

        $chartOptions = json_encode($generateChartData);
        $options = "
                const options_{$id} = $chartOptions;
            ";
        break;
    case 'bubble':

        $max_x = count($values) + 0.5;

        $generateChartData = [
            'series' => generate_bubble_chart_series($values, $labels),
            'chart' => [
                'type' => $chartType,
                'height' => 600,
                'zoom' => [
                    'enabled' => false
                ]
            ],
            'colors' => $colors,
            'xaxis' => [
                'tickAmount' => 1,
                'min' => 0.5,
                'max' => $max_x,
                'labels' => [
                    'show' => false,
                ]
            ],
            'yaxis' => [
                'show' => true,
            ],
            'plotOptions' => [
                'bubble' => [
                    'zScaling' => true,
                    'minBubbleRadius' => 15,
                    'maxBubbleRadius' => 150,
                ]
            ],
            'tooltip' => [
                'x' => [
                    'show' => false
                ],
                'z' => [
                    'title' => ''
                ]
            ],
            'dataLabels' => [
                'enabled' => true,
            ]
        ];

        if ($min_y) {
            $generateChartData['yaxis']['min'] = (int) $min_y;
        }
        if ($max_y) {
            $generateChartData['yaxis']['max'] = (int) $max_y;
        }

        $chartOptions = json_encode($generateChartData);

        $options = "
                const options_{$id} = $chartOptions;

                options_{$id}.tooltip.z.formatter = function (val) { return };
                options_{$id}.dataLabels.formatter = function (val, opts) { 
                    return ;
                    if(val){
                        return opts.w.globals.seriesNames[opts.seriesIndex] +': '+opts.w.globals.seriesTotals[opts.seriesIndex] ;
                    }
                };
                
            ";

        break;
}

/**
 * =================================================
 * HTML output of the chart block
 * =================================================
 */

$wrapperAttributes = wp_kses_data(
    get_block_wrapper_attributes(
        array(
            'id' => esc_attr($id),
            'class' => "{$class_name}",
        )
    )
)
    ?>
<?php if ($is_preview) {
    ?>
    <div class="chartOptions" data-options='<?php echo $chartOptions; ?>'></div>
<?php } ?>
<section <?php echo $wrapperAttributes; ?>>
    <div class="container-full">
        <div class="wrapper bg-n-0 p-4 sm:py-8 sm:px-16">
            <?php
            if ($title) { ?>
                <h6 class="mb-2">
                    <?php echo $title; ?>
                </h6>
            <?php } ?>

            <?php if ($description) { ?>
                <p class="text-sm leading-relaxed text-excerpt">
                    <?php echo $description; ?>
                </p>
            <?php } ?>
            <div id="<?php echo "chart_{$id}"; ?>"></div>
        </div>
    </div>
</section>

<?php

if (!$is_preview) {
    $javascript = "
    jQuery(function ($) {
        {$options}
        const chart_{$id} = new ApexCharts(document.querySelector('#chart_{$id}'), options_{$id});
        chart_{$id}.render();
    });
    ";

    wp_add_inline_script('apex-chart', $javascript, 'after');
}