jQuery(document).ready(function ($) {

    // +wordpress+theme+in:description+in:readme+in:name
    // +wordpress+plugin+in:description+in:readme+in:name
    // +wordpress+plugin+in:readme,name,description

    // top plugins on page load    
    if ($("#searchgit").length) // search only if in search git page
        Gitsearch(1);

    /* Bootstrap modal */
    //var frameSrc = "/login";


    $(document).on("click", ".wpgit_install", function (e) {

        alert('Direct install from here comming soon');

        e.preventDefault();

    });


    var page = 1;
    var nbrresults = 21;

    $("form#gitsearch").submit(function (event) {
        event.preventDefault();

        Gitsearch(page);
        $('#previous').css("display", "none");
        //$('#prevnext').css("display", "block");

    });

    function Gitsearch(page) {

        var searchTerm = $('#searchgit').val();
        var select = $('select#options option:selected').val();
        searchGithub(searchTerm, select, page);

        $('#Next').attr('data-count', page + 1);
        $('#previous').attr('data-count', page - 1);

    }

    $("#previous").click(function (event) {
        event.preventDefault();

        var page = $(this).attr("data-count");
        Gitsearch(page);

        page = parseInt(page);
        $(this).attr('data-count', page - 1);
        $('#Next').attr('data-count', page + 1);
        var src = $(this).attr("data-count");

        if (page - 1 === 0) $(this).css("display", "none");

        scrolltop();

    });

    $("#Next").click(function (event) {
        event.preventDefault();

        var page = $(this).attr("data-count");
        Gitsearch(page);

        page = parseInt(page);
        $(this).attr('data-count', page + 1);
        $('#previous').attr('data-count', page - 1);
        var src = $(this).attr("data-count");

        $('#previous').css("display", "inline-block");

        scrolltop();

    });

    function scrolltop() {
        //var body = $("#gitsearch");
        $("html, body").animate({
            scrollTop: $('#gitsearch').offset().top - 50
        }, 500);

    }


    function searchGithub(searchTerm, select, page) {

        // &sort=stars
        var url = "https://api.github.com/search/repositories";
        var html = '';

        var request = {
            q: searchTerm + select,
            sort: "repositories",
            order: "desc",
            page: page,
            per_page: nbrresults, // max 100
            //sort: "stars"
            //in: "wordpress"
        };

        var result = $.ajax({
                url: url,
                data: request,
                dataType: 'jsonp',
                type: 'GET'
            })
            .done(function (gh_data) {

                var optionstxt = $("#options option:selected").text();
                options = optionstxt.substring(0, optionstxt.length - 1);

                // GC Split words and trim and remove extra space
                //var str = searchTerm + ' <i>'+options+'</i>';
                var str = searchTerm + ' ' + options + ' ' + optionstxt;
                str = str.trim().replace(/\"+/g, '').replace(/\s+/g, ' ');
                var wordsToBold = str.split(" ");
                console.log(wordsToBold);

                timeremaining(gh_data.meta['X-RateLimit-Remaining']);

                //alert(Remaining);
                var total_count = gh_data.data.total_count;

                $('#nbrresults').html(total_count + ' Results in <b>' + optionstxt + '</b> for: <b>' + searchTerm + '</b>');

                $('#wpgit_results').html('');

                $.each(gh_data.data.items, function (i, item) {

                    var name = item.name;
                    name = name.replace(/-/g, ' ').replace(/_/g, ' ');

                    var description = item.description;
                    description = makeBold(description, wordsToBold)

                    //alert(timeSince('2015-11-23T23:00:32Z'));
                    var timeago = timeSince(item.updated_at) + ' ago';

                    html += '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div class="well well-sm"><div class="row">';
                    html += '<div class="col-xs-3 col-md-3 text-center paddingRight">';
                    html += '<img src="' + item.owner.avatar_url + '" class="img-rounded img-responsive" /></div>';

                    html += '<div class="col-xs-9 col-md-9 section-box">';
                    html += '<h3 class="wpgit_name">' + name + '</h3>';
                    html += '<p class="wpgit_description">' + description + '</p><hr />';

                    html += '<div class="wpgit_details">';

                    //html += '<span class="glyphicon glyphicon-comment"></span>(100 Comments) | ';


                    html += '<span class="url"><i class="glyphicon glyphicon-eye-open"></i><a href="' + item.html_url + '" target="_blank">View on Github</a></span><span class="separator">|</span>';
                    html += '<span class="download"><i class="glyphicon glyphicon-download-alt"></i><a href="' + item.html_url + '/archive/' + item.default_branch + '.zip">Download</a></span><br>';
                    html += '<i class="glyphicon glyphicon-star"></i>(' + item.watchers + ')<span class="separator">|</span>';
                    html += '<span class="update"><i class="glyphicon glyphicon-saved"></i>Last Update: ' + timeago + '</span>';

                    //alert(plugin_url);
                    // open details
                    //html += '<div class="plug_details"><a href="'+plugin_url+'inc/wp_readme-parser.php?githubfolder='+item.full_name+'">Details</a></div>';
                    //thickbox
                    //html += '<div id="plug_details"><i class="glyphicon glyphicon-export"></i><a href="" class="thickbox">More Details</a></div>';

                    html += '<div class="pull-right wpgit_buttons">' +
                        '<a href="' + plugin_url + 'inc/readme-parser.php?githubfolder=' + item.full_name + '&branch=' + item.default_branch + '&TB_iframe=true&width=772&height=720" class="plug_details thickbox btn btn-default" role="button">More Details</a> ' +
                        '<a href="#" class="btn btn-primary wpgit_install" role="button">Install</a>' +
                        '</div>';

                    html += '</div></div></div></div></div></div></div></div></div>';

                    //$("#results").append('<div class="row"><img src="'+item.owner.avatar_url+'" class="avatar"><span>'+item.description+'</span><span class="url"><a href="'+item.html_url+'" target="_blank">View on Github</a></span></div>');

                });

                //html += '<div id="prevnext"><span class="previous">< Previous </span> <span class="Next"> | Next ></span></div>';

                $("#wpgit_results").html(html);
                $('#prevnext').css("display", "block");
                if (total_count === 0) $('#prevnext').css("display", "none");
                else $('#prevnext').css("display", "inline-block");

                // Bold text
                if (searchTerm) {
                    var Bsearch = new RegExp(searchTerm, 'gi');
                } else {
                    var Bsearch = new RegExp('WPGIT.ORG', 'gi');
                }

                options = options.substring(0, options.length - 1);
                var Boptions = new RegExp(options, 'gi');


            })
            .fail(function () {
                alert('failed');
            });

    }

    // Function Time remaining
    function timeremaining(Remaining) {
        var date = new Date();
        var hour = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var time = hour + 'h' + minutes;

        var remain = 60 - seconds;

        if (Remaining === '0') alert(Remaining + ' Api call exceeded, wait a minute before searching again');

        $('#limit_remaining').html('<b>' + Remaining + '</b> Api call remaining for the next ' + remain + ' seconds: ' + time);

    }

    function makeBold(input, wordsToBold) {
        return input.replace(new RegExp('(\\b)(' + wordsToBold.join('|') + ')(\\b)', 'ig'), '$1<b>$2</b>$3');
    }

    // Function TimeSince Time ago
    var DURATION_IN_SECONDS = {
        epochs: ['year', 'month', 'day', 'hour', 'minute'],
        year: 31536000,
        month: 2592000,
        day: 86400,
        hour: 3600,
        minute: 60
    };

    function getDuration(seconds) {
        var epoch, interval;

        for (var i = 0; i < DURATION_IN_SECONDS.epochs.length; i++) {
            epoch = DURATION_IN_SECONDS.epochs[i];
            interval = Math.floor(seconds / DURATION_IN_SECONDS[epoch]);
            if (interval >= 1) {
                return {
                    interval: interval,
                    epoch: epoch
                };
            }
        }

    };

    function timeSince(date) {
        var seconds = Math.floor((new Date() - new Date(date)) / 1000);
        var duration = getDuration(seconds);
        var suffix = (duration.interval > 1 || duration.interval === 0) ? 's' : '';
        return duration.interval + ' ' + duration.epoch + suffix;
    };


});
