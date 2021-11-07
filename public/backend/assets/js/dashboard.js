(function($) {
  "use strict";

  window.onload = function() {
    var link = _url + "/dashboard/month_wise_sales_widget";
    $.ajax({
      url: link,
      success: function(data) {
        var json = JSON.parse(data);

        if (json.length == 0) {
          return;
        }

        var chartData = {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Sales",
              backgroundColor: "#6076ff",
              borderColor: "#6076ff",
              data: [
                typeof json["Jan"] !== "undefined" ? json["Jan"]["amount"] : 0,
                typeof json["Feb"] !== "undefined" ? json["Feb"]["amount"] : 0,
                typeof json["Mar"] !== "undefined" ? json["Mar"]["amount"] : 0,
                typeof json["Apr"] !== "undefined" ? json["Apr"]["amount"] : 0,
                typeof json["May"] !== "undefined" ? json["May"]["amount"] : 0,
                typeof json["Jun"] !== "undefined" ? json["Jun"]["amount"] : 0,
                typeof json["Jul"] !== "undefined" ? json["Jul"]["amount"] : 0,
                typeof json["Aug"] !== "undefined" ? json["Aug"]["amount"] : 0,
                typeof json["Sep"] !== "undefined" ? json["Sep"]["amount"] : 0,
                typeof json["Oct"] !== "undefined" ? json["Oct"]["amount"] : 0,
                typeof json["Nov"] !== "undefined" ? json["Nov"]["amount"] : 0,
                typeof json["Dec"] !== "undefined" ? json["Dec"]["amount"] : 0,
              ],
              yAxisID: "y",
            },
            {
              label: "Orders",
              backgroundColor: "#ef5350",
              borderColor: "#ef5350",
              data: [
                typeof json["Jan"] !== "undefined"
                  ? json["Jan"]["order_count"]
                  : 0,
                typeof json["Feb"] !== "undefined"
                  ? json["Feb"]["order_count"]
                  : 0,
                typeof json["Mar"] !== "undefined"
                  ? json["Mar"]["order_count"]
                  : 0,
                typeof json["Apr"] !== "undefined"
                  ? json["Apr"]["order_count"]
                  : 0,
                typeof json["May"] !== "undefined"
                  ? json["May"]["order_count"]
                  : 0,
                typeof json["Jun"] !== "undefined"
                  ? json["Jun"]["order_count"]
                  : 0,
                typeof json["Jul"] !== "undefined"
                  ? json["Jul"]["order_count"]
                  : 0,
                typeof json["Aug"] !== "undefined"
                  ? json["Aug"]["order_count"]
                  : 0,
                typeof json["Sep"] !== "undefined"
                  ? json["Sep"]["order_count"]
                  : 0,
                typeof json["Oct"] !== "undefined"
                  ? json["Oct"]["order_count"]
                  : 0,
                typeof json["Nov"] !== "undefined"
                  ? json["Nov"]["order_count"]
                  : 0,
                typeof json["Dec"] !== "undefined"
                  ? json["Dec"]["order_count"]
                  : 0,
              ],
              yAxisID: "y1",
            },
          ],
        };

        var ctx = document.getElementById("month_wise_sales").getContext("2d");

        window.myBar = new Chart(ctx, {
          type: "bar",
          data: chartData,
          options: {
            title: {
              display: false,
            },
            interaction: {
              mode: "index",
              intersect: false,
            },
            responsive: true,
            stacked: true,
            scales: {
              y: {
                type: "linear",
                display: true,
                position: "left",
                ticks: {
                  callback: function(value, index, values) {
                    return _currency_symbol + " " + value;
                  },
                },
              },
              y1: {
                type: "linear",
                display: true,
                position: "right",
                min: 1,
                grid: {
                  drawOnChartArea: false,
                },
              },
            },
            plugins: {
              tooltip: {
                callbacks: {
                  label: function(context) {
                    var label = context.dataset.label || "";

                    if (
                      context.parsed.y !== null &&
                      context.dataset.yAxisID == "y"
                    ) {
                      label += ": " + _currency_symbol + context.parsed.y;
                    } else {
                      label += ": " + context.parsed.y;
                    }

                    return label;
                  },
                },
              },
            },
          },
        });
      },
    });
  };
})(jQuery);
