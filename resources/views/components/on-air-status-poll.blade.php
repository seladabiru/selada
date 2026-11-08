<script>
    (() => {
        let requestInFlight = false;

        async function syncOnAirStatus() {
            if (requestInFlight) return;
            requestInFlight = true;

            try {
                const response = await fetch('{{ route('radio.status') }}', {
                    headers: { Accept: 'application/json' },
                    cache: 'no-store',
                });

                if (!response.ok) {
                    throw new Error(`Status siaran gagal dimuat (${response.status})`);
                }

                const data = await response.json();
                if (typeof data.is_on_air !== 'boolean') {
                    throw new Error('Status siaran menerima respons yang tidak valid');
                }

                updateIndicators(data.is_on_air);
            } catch (error) {
                console.error('Gagal menyinkronkan status siaran:', error);
            } finally {
                requestInFlight = false;
            }
        }

        function updateIndicators(isOnAir) {
            document.querySelectorAll('[data-on-air-indicator="on"]').forEach((indicator) => {
                indicator.classList.toggle('hidden', !isOnAir);
                if (indicator.dataset.display === 'flex') indicator.classList.toggle('flex', isOnAir);
                if (indicator.dataset.display === 'inline') indicator.classList.toggle('inline-flex', isOnAir);
            });

            document.querySelectorAll('[data-on-air-indicator="off"]').forEach((indicator) => {
                indicator.classList.toggle('hidden', isOnAir);
                if (indicator.dataset.display === 'flex') indicator.classList.toggle('flex', !isOnAir);
                if (indicator.dataset.display === 'inline') indicator.classList.toggle('inline-flex', !isOnAir);
            });
        }

        syncOnAirStatus();
        window.setInterval(syncOnAirStatus, 2000);
    })();
</script>
