$(document).ready(function () {
  //sidebar-active-tab start
  $("li.open").prev("li").addClass("before-open");
  $("li.open").next("li").addClass("after-open");
  //side-bar-active-tab end

  //side-bar-tooltip start
  $(".sideMenuTitle").click(function () {
    $(".sideBarDiv").toggleClass("collapsed-sidebar");
    $(".dashWelcomeSubtext p").toggleClass("visible");
    $(".custom-opacity").toggleClass("opacity-100");

    if ($(".sideBarDiv").hasClass("collapsed-sidebar")) {
      $('[data-bs-toggle="tooltip"]').tooltip();
    } else {
      $('[data-bs-toggle="tooltip"]').tooltip("dispose");
    }
  });
  //side-bar-tooltip end


  //responsive input start
  $(".headerInputSVG").click(function () {
    $(".responsive-search-wrap").addClass("open");
  });
  $(".resSearchClosebtn").click(function () {
    $(".responsive-search-wrap").removeClass("open");
  });
  //responsive input end

  //add-service-table-show start
  $("#add-service-table-check").on("change", function () {
    if ($("#add-service-table-check").is(":checked")) {
      $(".service-table").addClass("show");
    } else {
      $(".service-table").removeClass("show");
    }
  });
  //add-service-table-show end

  //datepicker js start
  // $(".datepicker").datepicker({
  //   dateFormat: "MM yy",
  // });
  // $(".slider-datepicker").datepicker({
  //   dateFormat: "MM yy",
  // });
  // $(".m-acc-created").datepicker({
  //   dateFormat: "dd MM yy",
  // });
  // $(".m-acc-due").datepicker({
  //   dateFormat: "dd MM yy",
  // });
  // $(".graphDatePicker").datepicker({
  //   dateFormat: "yy", // Show only the year
  //   changeMonth: false, // Disable month selection
  //   changeYear: true, // Enable year selection
  // });
  //datepicker js end

  //nested modal js start
  $("#nh-cat-input-add-wrap").on("click", function () {
    $("#add-btn-modal").modal("show");
  });
  $("#ac-modal-closebtn").on("click", function () {
    $("#add-btn-modal").modal("hide");
  });
  $("#bottom-close-btn").on("click", function () {
    $("#add-btn-modal").modal("hide");
  });
  //nested modal js end

  //responsive menu start
  $(".hamburger-icon").click(function () {
    $(".responsiveHeaderMenu").toggleClass("open");
  });
  $(".dropdown-activater-1").click(function () {
    $(".dropdown-activater-1").toggleClass("open");
  });
  $(".dropdown-activater-2").click(function () {
    $(".dropdown-activater-2").toggleClass("open");
  });
  //responsive menu end

  //menu-table-drag-sort-start
  // $(".menu-table tbody")
  //   .sortable({
  //     handle: ".menu-table-icon",
  //   })
  //   .disableSelection();
  //menu-table-drag-sort-end

  //invoice payment doughnut start
  const data = {
    labels: ["$1,000.00", "$38.00"],
    datasets: [
      {
        data: [70, 30],
        backgroundColor: [
          "#1912D3", //blue 70%
          "#FF7664", //red 30%
        ],

        cutout: "20%",
        borderWidth: 0,
      },
    ],
  };
  const doughnutLabelsLine = {
    id: "doughnutLabelsLine",
    afterDraw(chart, args, options) {
      if (chart.tooltip._active && chart.tooltip._active.length) {
        const activePoint = chart.tooltip._active[0];
        const {
          ctx,
          chartArea: { top, bottom, left, right, width, height },
        } = chart;
        const { x, y } = chart
          .getDatasetMeta(activePoint.datasetIndex)
          .data[activePoint.index].tooltipPosition();

        // Draw lines and text only when hovering

        const halfheight = height / 2;
        const halfwidth = width / 2;
        const xLine = x >= halfwidth ? x + 50 : x - 50;
        const yLine = y >= halfheight ? y + 50 : y - 50;
        const extraLine = x >= halfwidth ? 20 : -20;

        // Line
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(xLine, yLine);
        ctx.lineTo(xLine, yLine + extraLine);
        ctx.strokeStyle = "black";
        ctx.stroke();

        // Text
        const padding = 15;
        const textYPosition = y >= halfheight ? "right" : "left";
        const textXPosition =
          textYPosition === "right" ? xLine + padding : xLine - padding;
        ctx.font = "12px Work Sans";
        ctx.textAlign = textYPosition;
        const baselinePos = y >= halfheight ? "top" : "bottom";
        ctx.textBaseline = baselinePos;
        ctx.fillStyle = "#172F78";
        ctx.fillText(
          chart.data.labels[activePoint.index],
          textXPosition,
          yLine + extraLine
        );
      }
    },
  };
  const config = {
    type: "doughnut",
    data,
    options: {
      layout: {
        padding: 20,
      },
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          enabled: false,
        },
      },
    },
    plugins: [doughnutLabelsLine],
  };
  const myChart = new Chart($("#dashPieChart"), config);
  //invoice payment doughnut end

  //wave chart js start

  var ctx = document.getElementById("waveChart").getContext("2d");
  var waveChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: [
        "2024 Jan",
        "2024 March",
        "2024 Jul",
        "2024 Sept",
        "2024 Nov",
        "2024 Dec",
      ],
      datasets: [
        {
          label: "Invoiced",
          data: [400, 250, 450, 400, 470, 560],
          backgroundColor: "rgb(255,255,255,0.6)",
          borderColor: "#FF7664",
          pointBackgroundColor: "#FF7664",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          fill: true,
          lineTension: 0.4,
        },
        {
          label: "Paid",
          data: [570, 1050, 1700, 800, 970, 1200],
          backgroundColor: "rgb(225,233,255,0.2)",
          borderColor: "#001DFF",
          pointBackgroundColor: "#001DFF",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          fill: true,
          lineTension: 0.4,
        },
      ],
    },
    options: {
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: "Year",
          },
        },
        y: {
          ticks: {
            min: 0,
            max: 2000,
            stepSize: 500,
            callback: function (value, index, values) {
              return "$" + value;
            },
          },
          display: true,
          scaleLabel: {
            display: true,
            labelString: "X Axis",
          },
        },
      },
      plugins: {
        title: {
          display: false,
        },
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              var label = context.label || "";
              if (label) {
                label = label.split(" ")[1];
                label += ": $" + context.raw;
              }
              return label;
            },
            title: function (tooltipItems, data) {
              return "";
            },
          },
          displayColors: false,
          backgroundColor: "#EBF5FF",
          bodyColor: "#172F78",
        },
      },
      maintainAspectRatio: false,
    },
  });

  //wave chart js end

  //pending domain chart start
  var pd_data = {
    labels: ["Dark", "Light"],
    datasets: [
      {
        label: "My Dataset",
        data: [70, 30], // Example data
        backgroundColor: [
          "#E3F8FA", // light
          "#0C6E7B", // dark
        ],
        borderWidth: 0,
      },
    ],
  };
  var pd_options = {
    responsive: true,
    maintainAspectRatio: false,
    cutoutPercentage: 50,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        enabled: false,
      },
    },
  };
  var pd_ctx = document.getElementById("pd-chart").getContext("2d");
  var pd_myDoughnutChart = new Chart(pd_ctx, {
    type: "doughnut",
    data: pd_data,
    options: pd_options,
  });
  //pending domain chart end

  //pending hosting chart start
  var ph_data = {
    labels: ["Dark", "Light"],
    datasets: [
      {
        label: "My Dataset",
        data: [70, 30], // Example data
        backgroundColor: [
          "#FFF9DD", // light
          "#90790D", // dark
        ],
        borderWidth: 0,
      },
    ],
  };
  var ph_options = {
    responsive: true,
    maintainAspectRatio: false,
    cutoutPercentage: 50,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        enabled: false,
      },
    },
  };
  var ph_ctx = document.getElementById("ph-chart").getContext("2d");
  var ph_myDoughnutChart = new Chart(ph_ctx, {
    type: "doughnut",
    data: ph_data,
    options: ph_options,
  });

  //pending hosting chart end

  //unpaid invoices chart start
  var ui_data = {
    labels: ["Dark", "Light"],
    datasets: [
      {
        label: "My Dataset",
        data: [70, 30], // Example data
        backgroundColor: [
          "#F4E5FD", // light
          "#611592", // dark
        ],
        borderWidth: 0,
      },
    ],
  };
  var ui_options = {
    responsive: true,
    maintainAspectRatio: false,
    cutoutPercentage: 50,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        enabled: false,
      },
    },
  };
  var ui_ctx = document.getElementById("ui-chart").getContext("2d");
  var ui_myDoughnutChart = new Chart(ui_ctx, {
    type: "doughnut",
    data: ui_data,
    options: ui_options,
  });
  //unpaid invoices chart end

  //active tickets chart start
  var at_data = {
    labels: ["Dark", "Light"],
    datasets: [
      {
        label: "My Dataset",
        data: [70, 30], // Example data
        backgroundColor: [
          "#FFE6E2", // light
          "#B42D16", // dark
        ],
        borderWidth: 0,
      },
    ],
  };
  var at_options = {
    responsive: true,
    maintainAspectRatio: false,
    cutoutPercentage: 50,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        enabled: false,
      },
    },
  };
  var at_ctx = document.getElementById("at-chart").getContext("2d");
  var at_myDoughnutChart = new Chart(at_ctx, {
    type: "doughnut",
    data: at_data,
    options: at_options,
  });
  //active tickets chart end
});
