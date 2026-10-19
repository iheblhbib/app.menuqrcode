
(function () {
    "use strict";
  
    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
      el = el.trim();
      if (all) {
        return [...document.querySelectorAll(el)];
      } else {
        return document.querySelector(el);
      }
    };
  
    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
      if (all) {
        select(el, all).forEach((e) => e.addEventListener(type, listener));
      } else {
        select(el, all).addEventListener(type, listener);
      }
    };
  
    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
      el.addEventListener("scroll", listener);
    };
  
    /**
     * Sidebar toggle
     */
    if (select(".toggle-sidebar-btn")) {
      on("click", ".toggle-sidebar-btn", function (e) {
        select("body").classList.toggle("toggle-sidebar");
      });
    }
  
    /**
     * Search bar toggle
     */
    if (select(".search-bar-toggle")) {
      on("click", ".search-bar-toggle", function (e) {
        select(".search-bar").classList.toggle("search-bar-show");
      });
    }
  
    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select("#navbar .scrollto", true);
    const navbarlinksActive = () => {
      let position = window.scrollY + 200;
      navbarlinks.forEach((navbarlink) => {
        if (!navbarlink.hash) return;
        let section = select(navbarlink.hash);
        if (!section) return;
        if (
          position >= section.offsetTop &&
          position <= section.offsetTop + section.offsetHeight
        ) {
          navbarlink.classList.add("active");
        } else {
          navbarlink.classList.remove("active");
        }
      });
    };
    window.addEventListener("load", navbarlinksActive);
    onscroll(document, navbarlinksActive);
  
    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select("#header");
    if (selectHeader) {
      const headerScrolled = () => {
        if (window.scrollY > 100) {
          selectHeader.classList.add("header-scrolled");
        } else {
          selectHeader.classList.remove("header-scrolled");
        }
      };
      window.addEventListener("load", headerScrolled);
      onscroll(document, headerScrolled);
    }
  
    /**
     * Back to top button
     */
    let backtotop = select(".back-to-top");
    if (backtotop) {
      const toggleBacktotop = () => {
        if (window.scrollY > 100) {
          backtotop.classList.add("active");
        } else {
          backtotop.classList.remove("active");
        }
      };
      window.addEventListener("load", toggleBacktotop);
      onscroll(document, toggleBacktotop);
    }
  
    /**
     * Initiate tooltips
     */
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  
    /**
     * Initiate quill editors
     */
    // if (select(".quill-editor-default")) {
    //   new Quill(".quill-editor-default", {
    //     theme: "snow",
    //   });
    // }
  
    // if (select(".quill-editor-bubble")) {
    //   new Quill(".quill-editor-bubble", {
    //     theme: "bubble",
    //   });
    // }
  
    // if (select(".quill-editor-full")) {
    //   new Quill(".quill-editor-full", {
    //     modules: {
    //       toolbar: [
    //         [
    //           {
    //             font: [],
    //           },
    //           {
    //             size: [],
    //           },
    //         ],
    //         ["bold", "italic", "underline", "strike"],
    //         [
    //           {
    //             color: [],
    //           },
    //           {
    //             background: [],
    //           },
    //         ],
    //         [
    //           {
    //             script: "super",
    //           },
    //           {
    //             script: "sub",
    //           },
    //         ],
    //         [
    //           {
    //             list: "ordered",
    //           },
    //           {
    //             list: "bullet",
    //           },
    //           {
    //             indent: "-1",
    //           },
    //           {
    //             indent: "+1",
    //           },
    //         ],
    //         [
    //           "direction",
    //           {
    //             align: [],
    //           },
    //         ],
    //         ["link", "image", "video"],
    //         ["clean"],
    //       ],
    //     },
    //     theme: "snow",
    //   });
    // }
  
    /**
     * Initiate TinyMCE Editor
     */
  
    var useDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
  
    tinymce.init({
      selector: "textarea.tinymce-editor",
      plugins:
        "print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons",
      imagetools_cors_hosts: ["picsum.photos"],
      menubar: "file edit view insert format tools table help",
      toolbar:
        "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl",
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: "30s",
      autosave_prefix: "{path}{query}-{id}-",
      autosave_restore_when_empty: false,
      autosave_retention: "2m",
      image_advtab: true,
      link_list: [
        {
          title: "My page 1",
          value: "https://www.tiny.cloud",
        },
        {
          title: "My page 2",
          value: "http://www.moxiecode.com",
        },
      ],
      image_list: [
        {
          title: "My page 1",
          value: "https://www.tiny.cloud",
        },
        {
          title: "My page 2",
          value: "http://www.moxiecode.com",
        },
      ],
      image_class_list: [
        {
          title: "None",
          value: "",
        },
        {
          title: "Some class",
          value: "class-name",
        },
      ],
      importcss_append: true,
      file_picker_callback: function (callback, value, meta) {
        /* Provide file and text for the link dialog */
        if (meta.filetype === "file") {
          callback("https://www.google.com/logos/google.jpg", {
            text: "My text",
          });
        }
  
        /* Provide image and alt text for the image dialog */
        if (meta.filetype === "image") {
          callback("https://www.google.com/logos/google.jpg", {
            alt: "My alt text",
          });
        }
  
        /* Provide alternative source and posted for the media dialog */
        if (meta.filetype === "media") {
          callback("movie.mp4", {
            source2: "alt.ogg",
            poster: "https://www.google.com/logos/google.jpg",
          });
        }
      },
      templates: [
        {
          title: "New Table",
          description: "creates a new table",
          content:
            '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>',
        },
        {
          title: "Starting my story",
          description: "A cure for writers block",
          content: "Once upon a time...",
        },
        {
          title: "New list with dates",
          description: "New List with dates",
          content:
            '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>',
        },
      ],
      template_cdate_format: "[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]",
      template_mdate_format: "[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]",
      height: 600,
      image_caption: true,
      quickbars_selection_toolbar:
        "bold italic | quicklink h2 h3 blockquote quickimage quicktable",
      noneditable_noneditable_class: "mceNonEditable",
      toolbar_mode: "sliding",
      contextmenu: "link image imagetools table",
      skin: useDarkMode ? "oxide-dark" : "oxide",
      content_css: useDarkMode ? "dark" : "default",
      content_style:
        "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
    });
  
    /**
     * Initiate Bootstrap validation check
     */
    var needsValidation = document.querySelectorAll(".needs-validation");
  
    // Array.prototype.slice.call(needsValidation)
    //   .forEach(function(form) {
    //     form.addEventListener('submit', function(event) {
    //       if (!form.checkValidity()) {
    //         event.preventDefault()
    //         event.stopPropagation()
    //       }
  
    //       form.classList.add('was-validated')
    //     }, false)
    //   })
  
    /**
     * Initiate Datatables
     */
    const datatables = select(".datatable", true);
    datatables.forEach((datatable) => {
      new simpleDatatables.DataTable(datatable);
    });
  
    /**
     * Autoresize echart charts
     */
    const mainContainer = select("#main");
    if (mainContainer) {
      setTimeout(() => {
        new ResizeObserver(function () {
          select(".echart", true).forEach((getEchart) => {
            echarts.getInstanceByDom(getEchart).resize();
          });
        }).observe(mainContainer);
      }, 200);
    }
  })();
  
  function ucwords(str) {
    return (str + "").replace(/^([a-z])|\s+([a-z])/g, function ($1) {
      return $1.toUpperCase();
    });
  }
  
  function notification() {
    var x = document.getElementById("message");
    x.className = "show";
    setTimeout(function () {
      x.className = x.className.replace("show", "");
    }, 3000);
  
    var html = $("#message").html();
    $("#message").html('<i class="fal fa-check"></i> ' + html + " !");
  }
  
  function notificationfailed() {
    var x = document.getElementById("messagefailed");
    x.className = "show";
    setTimeout(function () {
      x.className = x.className.replace("show", "");
    }, 3000);
  
    var html = $("#messagefailed").html();
    $("#messagefailed").html('<i class="fal fa-times"></i> ' + html + " !");
  }
  
  $("#buttonlogoutModal").click(function () {
    $("#logoutModal").modal("show");
  });
  
  $(document).ready(function () {
    $(".fullscreen_button").on("click", function () {
      (document.fullScreenElement && null !== document.fullScreenElement) ||
      (!document.mozFullScreen && !document.webkitIsFullScreen)
        ? document.documentElement.requestFullScreen
          ? document.documentElement.requestFullScreen()
          : document.documentElement.mozRequestFullScreen
          ? document.documentElement.mozRequestFullScreen()
          : document.documentElement.webkitRequestFullScreen &&
            document.documentElement.webkitRequestFullScreen(
              Element.ALLOW_KEYBOARD_INPUT
            )
        : document.cancelFullScreen
        ? document.cancelFullScreen()
        : document.mozCancelFullScreen
        ? document.mozCancelFullScreen()
        : document.webkitCancelFullScreen && document.webkitCancelFullScreen();
    });
  });
  
  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }
  function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
  
  const txHeight = 16;
  const tx = document.getElementsByTagName("textarea");
  
  $(document).ready(function () {
    // on click notification-item
    $(".notification-item").click(function () {
      // redirect into notification
      window.location.href = 'notification';
    });
  });
  
  const notificationSwal = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  
  
  $(document).on('click', '.all_readed', function(){
  
      $.ajax({
          url:"notification-action.php",
          method:"POST",
          data:{action:'allreaded'},
          success:function(data)
          {
              var html = `
                          <li class="dropdown-header">
                              Vous n'avez pas de notification pour le moment            
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li class="text-center">
                            <div class="">
                              <h4></h4>
                              <p>Aucune notification</p>
                            </div>
                          </li>
              
                          `;
              Swal.fire(
                  'Notifications lues',
                  "Toutes les notifications marquées comme lues",
                  'success'
              ) 
              $('.notifications').html(html);
              $('.badge-number').html('');
          }
      });
  });
  
      function showNotification(id) {
  
              $.ajax({
                  url : "api/notificationById.php",
                  type: "POST",
                  dataType:'json',
                  data: {id:id},
                  success: function(data) {
                      console.log(data)
                      askForApproval(data.title, data.msg, data.icon);
                  }
              });
          
      };
      
  
        function askForApproval(title, text, icon) {
          if(Notification.permission === "granted") {
            createNotification(title, text, icon);
          }
          else {
            Notification.requestPermission(permission => {
              if(permission === 'granted') {
                createNotification(title, text,icon);
              }
            });
          }
        }
  
        function createNotification(title, text, icon) {
          const noti = new Notification(title, {
            body: text,
            icon
          });
        }
  
  
        function getRandomNumberBetween(min,max){
          return Math.floor(Math.random()*(max-min+1)+min);
      }
      $(".permalink").click(function(){
          var link = $(this).data('link');
  
          // redirect to link
          window.location.href = link;
      })
  