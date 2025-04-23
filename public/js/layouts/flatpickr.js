flatpickr.localize(flatpickr.l10ns.id);

const datepickers = document.querySelectorAll(".fp-datepicker");
datepickers.forEach((el) => {
  initializeDatepicker(el);
});

function initializeDatepicker(el) {
  const settings = {
    altInput: true,
    altFormat: "l, j F Y",
    dateFormat: "Y-m-d",
    defaultDate: el.value,
    disableMobile: true,
  };

  const plugin = el.getAttribute("data-plugin");
  switch (plugin) {
    case "month":
      settings.plugins = [
        new monthSelectPlugin({
          shorthand: true,
          dateFormat: "Y-m-d",
          altFormat: "F Y",
        }),
      ];
      break;
    default:
      break;
  }

  flatpickr(el, settings);
}

const timepickers = document.querySelectorAll(".fp-timepicker");
timepickers.forEach((el) => {
  initializeTimepicker(el);
});

function initializeTimepicker(el) {
  const settings = {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    defaultDate: el.value,
    time_24hr: true,
    disableMobile: "true",
  };

  flatpickr(el, settings);
}
