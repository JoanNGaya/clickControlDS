$(document).ready( function() {

   $('body').on('click', '.book-card', function(e) {

      alert('click');

      var isbn = $(this).find('.isbn').text();
      var url = 'api/' + isbn;
      url = url.split(' ').join('');

      jQuery.get(url, function(data) {
         if (data.length) {
            showBook(data[0]);
         }
      });
   });

   $('body').on('click', '.delete-book', function(e) {
      e.stopPropagation();

      var $card = $(this).parent();
      var id = $card.find('.book-id').text();
      var url = 'api/' + id;

      $.ajax({
         url: url,
         type: 'DELETE',
         success: function(response) {
            if (response.status == 'Book deleted') {
               $card.fadeOut('slow');
            }
         }
      });
   });

    function showBook(book) {
      console.log(book);
   }


});