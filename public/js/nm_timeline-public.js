(function ($) {
  "use strict";
  $(function () {
    const MutationObserver = window.MutationObserver;
    const myObserver = new MutationObserver((mutation) => {
      updateSliderDate(mutation[0].target.style.top);
    });
    const dates = $(".nmt-date");
    const separator = $(".nmt-side-mid-separator");

    let dragInitiated = false;
    let originalSliderPos = 0;
    let originalCurPos = 0;

    const events = [];
    let eventIteration = 0;

    dates.each(function () {
      let separatorPointY;
      if (eventIteration == 0) {
        separatorPointY = 0;
      } else if (eventIteration == dates.length - 1) {
        separatorPointY = separator.height() - 1;
      } else {
        separatorPointY = $(this).position().top - 1;
      }
      events.push({
        id: $(this).data("id"),
        date: $(this).data("date"),
        top: separatorPointY,
      });
      eventIteration++;
    });

    events.forEach(function (event) {
      separator.append(
        '<div class="nmt-separator-point" style="top:' +
          event.top +
          'px;"></div>'
      );
    });

    function stickToNearest() {
      let sliderPos = $(".nmt-side-slider").position().top;

      let nearest = events.reduce(function (prev, cur) {
        return Math.abs(cur.top - sliderPos) < Math.abs(prev.top - sliderPos)
          ? cur
          : prev;
      });

      $(".nmt-side-slider").animate({ top: nearest.top });
    }

    $(".nmt-side-slider").each(function () {
      myObserver.observe(this, { attributes: true });
    });

    function updateSliderDate(sliderTop) {
      let top = parseInt(sliderTop);
      for (let i = 0; i <= events.length - 1; i++) {
        if (top > events[i].top && top < events[i + 1].top) {
          let topOverDate = top - events[i].top;
          let incrementPerTop =
            (events[i + 1].date - events[i].date) /
            (events[i + 1].top - events[i].top);
          let updatedDate = Math.round(
            events[i].date + topOverDate * incrementPerTop
          );
          $(".nmt-side-slider-label").html(updatedDate);
        }
      }
    }

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
        stickToNearest();
      })
      .mousemove(function (event) {
        if (dragInitiated) {
          let curChange = -1 * (originalCurPos - event.pageY);
          let sliderPos = originalSliderPos + curChange;
          if (sliderPos < 0) {
            sliderPos = 0;
          } else if (sliderPos > separator.height()) {
            sliderPos = separator.height();
          } else {
            $(".nmt-side-slider").css("top", sliderPos + "px");
          }
        }
      });
  });
})(jQuery);
