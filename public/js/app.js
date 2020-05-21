$(document).ready( function() {

   $('body').on('click', '.more-info-book', function(e) {

      var isbn = $(this).closest('.book-card').find('.isbn').text();
      console.log(isbn);
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

      var $card = $(this).closest('.book-card');
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

       var $modal = $('.book-modal');

       $modal.find('.modal-title').text(book.title);
       $modal.find('.modal-title').append('<span>' + book.subtitle + '</span>');
       $modal.find('.modal-book-subtitle').text(book.subtitle);
       $modal.find('.modal-book-isbn').text('Isbn: ' + book.isbn);
       $modal.find('.modal-book-author').text(book.author);
       $modal.find('.modal-book-category').text(book.category);
       $modal.find('.modal-book-description').text(book.description);

       $modal.modal('show');
   }

});