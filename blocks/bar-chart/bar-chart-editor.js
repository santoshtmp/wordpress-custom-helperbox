jQuery(function ($) {
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=seap/barchart', (block) => {
      // find the main section with id
      const section = block.find('section');
      const id = `chart_${section.attr('id')}`;

      // reset chart for rerender
      $(`#${id}`).remove();
      $(section).append(`<div id="${id}"></div>`);

      // initialize chart

      setTimeout(function () {
        const chartOptions = block.find('.chartOptions').data('options');
        if (chartOptions.chart.type == 'bubble') {
          chartOptions.tooltip.z.formatter = function (val) { return };
          chartOptions.dataLabels.formatter = function (val, opts) {
              return;
          };
        }
        const chart = new ApexCharts(block.find(`#${id}`)[0], chartOptions);
        chart.render();
      }, 1000);
    });
  }
});
