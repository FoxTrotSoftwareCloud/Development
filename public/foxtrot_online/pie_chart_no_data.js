var chart_id = document.currentScript.getAttribute( 'chart_id' ); //Sent as a parameter from the page

var ctx = $( '#' + chart_id );
var config = {
	type: 'pie',
	data: {}, //Will be filled out through PHP
	options: {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			onHover: function(e) {
				e.target.style.cursor = 'pointer';
			},
			position: 'right',
			labels: {
				generateLabels: function(chart) {
					var data = chart.data;
					if (data.labels.length && data.datasets.length) {
						return data.labels.map(function(label, i) {
							var meta = chart.getDatasetMeta(0);
							var ds = data.datasets[0];
							var arc = meta.data[i];
							var custom = arc && arc.custom || {};
							var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
							var arcOpts = chart.options.elements.arc;
							var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
							var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
							var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

							// We get the value of the current label
							var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

							return {
								// Instead of `text: label,`
								// We add the value to the string
								text: label + ": $" + value,
								fillStyle: fill,
								strokeStyle: stroke,
								lineWidth: bw,
								hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
								index: i
							};
						});
					} else {
						return [];
					}
				}
			}
		},
		tooltips: {
			callbacks: {
				label: function(tooltipItem, data) {
					var dataset = data.datasets[tooltipItem.datasetIndex];
					var meta = dataset._meta[Object.keys(dataset._meta)[0]];
					var total = meta.total;
					var currentValue = dataset.data[tooltipItem.index];
					var percentage = parseFloat((currentValue/total*100).toFixed(1));
					return currentValue + ' (' + percentage + '%)';
				},
				title: function(tooltipItem, data) {
					var popup_header = data.labels[tooltipItem[0].index];
					/*popup_header = popup_header.toLowerCase().replace(/\b[a-z]/g, function(letter) {
						return letter.toUpperCase();
					});*/
					$(".pie_chart_modal_title").html(popup_header+' Trades');
					return data.labels[tooltipItem[0].index];
				}
			}
		},
		hover: {
			onHover: function(e) {
				var point = this.getElementAtEvent(e);
				if (point.length) e.target.style.cursor = 'pointer';
				else e.target.style.cursor = 'default';
			}
		}
	}
};

var pie_chart = new Chart( ctx, config );