(function ($) {
  "use strict";
  $(function () {
    const separator = $(".nmt-side-mid-separator");
    let events = [];

    /**
     * Create array of event objects
     */
    function initEvents() {
      events = [];
      let dates = $(".nmt-date");
      let eventIteration = 0;

      dates.each(function () {
        let separatorPointY;
        if (eventIteration == 0) {
          separatorPointY = 0;
        } else if (eventIteration == dates.length - 1) {
          separatorPointY = separator.height() - 1;
        } else {
          separatorPointY = $(this).position().top;
        }
        events.push({
          id: $(this).data("id"),
          date: $(this).data("date"),
          top: separatorPointY,
        });
        eventIteration++;
      });
    }

    initEvents();

    /**
     * Observe slider changes and update date label accordingly
     */
    let activeEventId = events[0].id;
    const MutationObserver = window.MutationObserver;
    const myObserver = new MutationObserver((mutation) => {
      let top = parseInt(mutation[0].target.style.top);
      let updatedDate;
      for (let i = 0; i <= events.length - 1; i++) {
        if (top == events[i].top) {
          updatedDate = events[i].date;
        } else if (top > events[i].top && top < events[i + 1].top) {
          let topOverDate = top - events[i].top;
          let incrementPerTop =
            (events[i + 1].date - events[i].date) /
            (events[i + 1].top - events[i].top);
          updatedDate = Math.round(
            events[i].date + topOverDate * incrementPerTop
          );
        }
        $(".nmt-side-slider-label").html(updatedDate);
      }
    });

    $(".nmt-side-slider").each(function () {
      myObserver.observe(this, { attributes: true });
    });

    /**
     * Find the nearest event date to slider, move slider to that
     * and render content for that date if not currently active
     */
    function selectNearestDate() {
      let sliderPos = $(".nmt-side-slider").position().top;
      let nearest = events.reduce(function (prev, cur) {
        return Math.abs(cur.top - sliderPos) < Math.abs(prev.top - sliderPos)
          ? cur
          : prev;
      });
      $(".nmt-side-slider").animate({ top: nearest.top }, 400, function () {
        if (nearest.id != activeEventId) {
          $(".nmt-content-event.active-event").fadeOut(400, function () {
            $(".nmt-content-event[data-id='" + nearest.id + "']")
              .fadeIn(400, function () {})
              .addClass("active-event");
            activeEventId = nearest.id;
            $(this).removeClass("active-event");
          });
        }
      });
    }

    /**
     * Listen to events for user interaction
     */
    let dragInitiated = false;
    let originalSliderPos = 0;
    let originalCurPos = 0;

    $(".nmt-side-slider").mousedown(function (event) {
      event.preventDefault();
      dragInitiated = true;
      originalCurPos = event.pageY;
      originalSliderPos = $(this).position().top;
      $("*").css("cursor", "grabbing");
    });

    $(window)
      .mouseup(function () {
        dragInitiated = false;
        $("*").css("cursor", "");
        selectNearestDate();
      })
      .mousemove(function (event) {
        if (dragInitiated) {
          let curChange = -1 * (originalCurPos - event.pageY);
          let sliderPos = originalSliderPos + curChange;
          if (sliderPos < 0) {
            sliderPos = 0;
          } else if (sliderPos > separator.height() - 1) {
            sliderPos = separator.height();
          } else {
            $(".nmt-side-slider").css("top", sliderPos + "px");
          }
        }
      })
      .resize(function (event) {
        initEvents();
      });
  });
})(jQuery);
