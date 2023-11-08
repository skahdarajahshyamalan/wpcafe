"use strict";
jQuery(document).ready(function ($) {
  let wrapperElUrls = $(".qrcode-option-container").data("qrcode");
  let wrapperElTableId = $(".qrcode-option-container").data("qrcode-id");
  if (Array.isArray(wrapperElUrls) && QRCode) {
    wrapperElUrls.map((item, i) => {
      let _item = item;
      if (Array.isArray(wrapperElTableId) && wrapperElTableId[i]) {
        _item = composeUrl(item, wrapperElTableId[i]);
      }
      QRCode.toDataURL(
        _item,
        {
          width: 400,
        },
        function (err, url) {
          if (!err) {
            const imgEl = document.querySelector(`.wpc-qr-img-${i}`);
            imgEl.innerHTML = `<div class="wpc-qr-img-wrapper" > <img width="140" class="wpc-qr-image" src=${url} /> 
           <div class="wpc-qr-action-wrapper">
              <a class="wpc-qr-download-btn" href="${url}" download >Download</a></div>
            </div>`;
          } else {
            //error handling...
            // const imgEl = document.querySelector(`.wpc-qr-img-${i}`);
            // imgEl.innerHTML = `<p>Something went wrong! </p>`;
          }
        }
      );
    });
  }

  function composeUrl(url, queryParam) {
    let _url = new URL(url);
    let _queryParam = queryParam.trim();
    _queryParam = _queryParam.split(' ').join('_');
    let path = _url.origin + _url.pathname.replace(/\/$/, "");
    let search = _url.search;

    const op = search ? "&" : "?";
    _url = search ? path + search : path;
    _url = _queryParam ? _url + `${op}tableId=${_queryParam}` : _url.toString();

    return _url;
  }

  $("#wpc-qr-print-btn").click(() => {
    window.print();
  });
});
