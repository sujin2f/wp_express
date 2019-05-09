jQuery(document).ready(function($) {
  var frame;
  $('.wp_express.btn-upload').click(function(e) {
    var id = $(this).attr('data-id');
    e.preventDefault();

    if( !frame ) {
      frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: { text: 'Use this media' },
        multiple: false
      });

      frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        $( '#' + id + '-custom-img-container' ).append( '<img src="' + attachment.url + '" width="150" />' );
        $( '#' + id + '-custom-img-id' ).val( attachment.id );

        $( '#' + id + '-upload-custom-img' ).addClass( 'hidden' );
        $( '#' + id + '-delete-custom-img' ).removeClass( 'hidden' );
      });
    }

    frame.open();
  });

  $('.wp_express.btn-remove').on('click', function(e) {
    var id = $(this).attr('data-id');
    e.preventDefault();

    $( '#' + id + '-custom-img-container' ).html( '' );
    $( '#' + id + '-custom-img-id' ).val( '' );

    $( '#' + id + '-upload-custom-img' ).removeClass( 'hidden' );
    $( '#' + id + '-delete-custom-img' ).addClass( 'hidden' );
  });
});
