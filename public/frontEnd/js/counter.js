function animateCounter(element, start, end, duration) {
    let current = start;
    const increment = (end - start) / (duration / 16);
    let animationFrameId;

    function updateCounter() {
        current += increment;
        element.textContent = formatCounter(current, end);

        if ((increment > 0 && current < end) || (increment < 0 && current > end)) {
            animationFrameId = requestAnimationFrame(updateCounter);
        } else {
            element.textContent = formatCounter(end, end);
            cancelAnimationFrame(animationFrameId); // Stop the animation frame
        }
    }

    updateCounter();
}

function formatCounter(number, end) {
    const endString = String(Math.abs(end));

    if (endString.includes('.')) {
        const decimalPlaces = endString.split('.')[1].length;
        return parseFloat(number).toFixed(decimalPlaces);
    } else if (Math.abs(end) >= 1000000) {
        return (parseFloat(number) / 1000000).toFixed(1) + 'M';
    } else if (Math.abs(end) >= 1000) {
        return Math.round(number / 1000) + 'K';
    } else {
        return Math.round(number);
    }
}

const counters = document.querySelectorAll('.counter');
const animationDuration = 2000;

function observeCounter(counter) {
    const targetValue = parseFloat(counter.textContent.replace(/[^\d.-]/g, ''));
    const startValue = 0;
    let hasAnimated = false; // Flag to track if animation has started

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
                animateCounter(counter, startValue, targetValue, animationDuration);
                hasAnimated = true;
            } else if (!entry.isIntersecting && hasAnimated) {
                // Reset the counter and the flag when it leaves the viewport
                counter.textContent = formatCounter(startValue, targetValue);
                hasAnimated = false;
                // Optionally, you could restart observing here if needed immediately
                // observer.observe(counter);
            }
        });
    }, {
        threshold: 0.5
    });

    observer.observe(counter);
}

function startCountersOnScroll() {
    counters.forEach(observeCounter);
}

startCountersOnScroll();