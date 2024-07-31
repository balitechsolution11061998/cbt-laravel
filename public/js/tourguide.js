document.addEventListener('DOMContentLoaded', () => {
    const intro = introJs();

    intro.setOptions({
        steps: [
            {
                element: '#dashboard-steps',
                intro: `
                    <h2 style="text-align: center; font-family: Arial, sans-serif; font-weight: bold;">Welcome to the Dashboard!</h2>
                    <div style="width: 100%; max-width: 600px; margin: 0 auto;">
                        <img src='/image/dashboard.svg' alt='Welcome Image' style='width: 100%; height: 200px;'>
                        <p style="text-align: center; font-family: Arial, sans-serif;">This is the dashboard overview.</p>
                        <button class='introjs-button introjs-nextpage-button' onclick='nextPage()' style='width: 100%;'>Next Page</button>
                    </div>
                `,
                position: 'bottom'
            },
            {
                element: '#cardCountRcv',
                intro: `
                    <h2 style="text-align: center; font-family: Arial, sans-serif; font-weight: bold;">Summary Receiving</h2>
                    <div style="width: 100%; max-width: 600px; margin: 0 auto;">
                        <p style="text-align: center; font-family: Arial, sans-serif;">This section provides a summary of all received items ${dateRangeText}.</p>
                        <img src='https://example.com/image2.jpg' alt='Summary Receiving Image' style='max-width: 100%; height: auto;'>
                        <button class='introjs-button introjs-nextpage-button' onclick='nextPage()' style='width: 100%;'>Next Page</button>
                    </div>
                `,
                position: 'bottom'
            },
            {
                element: '#cardCountPo',
                intro: `
                    <h2 style="text-align: center; font-family: Arial, sans-serif; font-weight: bold;">Summary Purchase Order</h2>
                    <div style="width: 100%; max-width: 600px; margin: 0 auto;">
                        <p style="text-align: center; font-family: Arial, sans-serif;">This is Page 2.</p>
                        <img src='https://example.com/image3.jpg' alt='Page 2 Image' style='max-width: 100%; height: auto;'>
                        <button class='introjs-button introjs-nextpage-button' onclick='nextPage()' style='width: 100%;'>Next Page</button>
                    </div>
                `,
                position: 'bottom'
            }
        ]
    });

    // Function to handle next page navigation
    function nextPage() {
        // Replace with your actual next page URL
        window.location.href = '/items';
    }

    document.getElementById('start-tour').addEventListener('click', () => {
        intro.start();
    });
});
