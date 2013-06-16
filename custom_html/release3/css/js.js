// JavaScript Document
    <script type='text/javascript'>

        $(document).ready(function () {

            //show the first signe news container using fadeIn
            $('.single_news_container:first').fadeIn(100);
            /*add news_visible class to the same container
             important: this class has no css effects, it's only used so we can know which
             news container is currently shown '*/
            $('.single_news_container:first').addClass('news_visible');

            //DECLARE VARIABLES WE WILL NEED
            var news_No = $('.single_news_container').length; //get number of single news containers
            var prev_html = '<img border=0 src="images/prev.png" style="vertical-align:middle">'; //the html inside <a>  for previous news
            var next_html = '<img border=0 src="images/next.png" style="vertical-align:middle">'; //the html inside <a>  for next news

            //fill the news_navigation container with the navigation html
            $('#news_navigation').html('<a href="javascript:prev_news();">' + prev_html + '</a> <span id="current_news_num">1</span>/' + news_No + ' <a href="javascript:next_news();">' + next_html + '</a>');
            /* explanation: we have - previous link , current news number, / , amount of news, next link
             so it looks like &lt; 1/5 &gt. As you can see in the href attribute inside our two <a> are
             links to function we will declare bellow, so when clicked those functions are called*/

        });

        //now we start our functions

        function prev_news() {

            //check if there is a single news container before the current visible container... 
            if ($('.news_visible').prev('.single_news_container').length) {

                //... and if there is we hide the current visible news container by using fadeOut...
                $('.news_visible').fadeOut(100, function () {
                    /*...and when the animation ends we have few chain events.
                     explanation: we first remove the news_visible class from the current visible news container
                     and apply it to the previous news container, and show that container by using fadeIn()...*/
                    $('.news_visible').removeClass('news_visible').prev('.single_news_container').addClass('news_visible').fadeIn(100);
                    //... then we get the current page number and using parseInt we convert it from text to number (string to integer)...
                    var current = parseInt($('#current_news_num').text());
                    //...and change the current page number
                    $('#current_news_num').text(current - 1);
                });
            }

        }

        /*bellow we make a function for next_news. I wont explain it line by line because it's almost the same like the previous function.
         the difference:
         1) instead of checking if there is previous news (by using prev()) we check if there is by using next()
         2) and in the chained events we change the prev() to next() so we show the next news div  '*/
        function next_news() {
            if ($('.news_visible').next('.single_news_container').length) {
                $('.news_visible').fadeOut(100, function () {
                    $('.news_visible').removeClass('news_visible').next('.single_news_container').addClass('news_visible').fadeIn(100);
                    var current = parseInt($('#current_news_num').text());
                    $('#current_news_num').text(current + 1);
                });
            }
        }

    </script>