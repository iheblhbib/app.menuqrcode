<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php


    
$event = json_decode($object->get_event($market->id));

if(!empty($event->titre) && !empty($event->banner)){
?>

<script>
    
      setTimeout(function(){ 
        Swal.fire({
          title: `<?=  $event->titre ?>`,
          html: `<?=  $event->texte ?>`,
          imageUrl: '<?=  $event->banner ?>',
          imageAlt: 'Évenement',
          showConfirmButton: false,  
          showCloseButton: true
        })
      }, 2000);
</script>

<?php } ?>