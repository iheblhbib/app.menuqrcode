<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$events = $object->get_events($market->id); // Assuming this returns a JSON array of events
$eventsArray = json_decode($events, true); // Decode as an associative array

if (!empty($eventsArray)) {
?>
<script>
    const events = <?= json_encode($eventsArray); ?>; // Pass PHP array to JavaScript
    let currentIndex = 0;

    function showNextEvent() {
        if (currentIndex < events.length) {
            const event = events[currentIndex];
            Swal.fire({
                title: event.titre,
                html: event.texte,
                imageUrl: event.banner,
                imageAlt: 'Évenement',
                showConfirmButton: false,
                showCloseButton: true
            }).then(() => {
                currentIndex++;
                showNextEvent(); // Show the next event after closing the current one
            });
        }
    }

    setTimeout(showNextEvent, 2000); // Start showing events after a delay
</script>
<?php
}
?>
