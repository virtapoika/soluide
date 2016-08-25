function showFiles() {
  $(".projects").width(0)
  $(".files").width( $(".left").width() )
}

function showProjects() {
  $(".projects").width( $(".left").width() )
  $(".files").width( 0 )
}
