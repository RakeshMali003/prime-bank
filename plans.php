
    <h1>Fixed Deposit Schemes</h1>
    <div class="fixed-deposit-section">
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Starter</div>
            <div class="card-details">
                <span class="check-icon">&#10003;</span> Lock-in Period: 3 months<br>
                <span class="check-icon">&#10003;</span> Get Profit Every Month<br>
                <span class="check-icon">&#10003;</span> Profit Rate: 3%<br>
                <span class="check-icon">&#10003;</span> Min-Max Amount: $1,000 - $5,000
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Basic</div>
            <div class="card-details">
                <span class="check-icon">&#10003;</span> Lock-in Period: 6 months<br>
                <span class="check-icon">&#10003;</span> Get Profit Every Month<br>
                <span class="check-icon">&#10003;</span> Profit Rate: 4%<br>
                <span class="check-icon">&#10003;</span> Min-Max Amount: $5,000 - $10,000
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Standard</div>
            <div class="card-details">
                <span class="check-icon">&#10003;</span> Lock-in Period: 12 months<br>
                <span class="check-icon">&#10003;</span> Get Profit Every Month<br>
                <span class="check-icon">&#10003;</span> Profit Rate: 5%<br>
                <span class="check-icon">&#10003;</span> Min-Max Amount: $10,000 - $50,000
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Premium</div>
            <div class="card-details">
                <span class="check-icon">&#10003;</span> Lock-in Period: 24 months<br>
                <span class="check-icon">&#10003;</span> Get Profit Every Month<br>
                <span class="check-icon">&#10003;</span> Profit Rate: 6%<br>
                <span class="check-icon">&#10003;</span> Min-Max Amount: $50,000 - $100,000
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
    </div>

    <h1 style="margin-top:200px">We Have The Best Loan Plans</h1>
    <div class="fixed-deposit-section">
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Education Loan</div>
            <div class="card-details">
                <span class="check-icon">&#10003;</span> Take Minimum $500.00<br>
                <span class="check-icon">&#10003;</span> Take Maximum $50,000.00<br>
                <span class="check-icon">&#10003;</span> Per Installment 5%<br>
                <span class="check-icon">&#10003;</span> Installment Interval 30 Days<br>
                <span class="check-icon">&#10003;</span> Total Installment 22<br>
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Basic</div>
            <div class="card-details">
            <span class="check-icon">&#10003;</span> Take Minimum $5000.00<br>
                <span class="check-icon">&#10003;</span> Take Maximum $50,000.00<br>
                <span class="check-icon">&#10003;</span> Per Installment 10%<br>
                <span class="check-icon">&#10003;</span> Installment Interval 30 Days<br>
                <span class="check-icon">&#10003;</span> Total Installment 22<br>
            </div>
            <a href="#" class="apply-button">Apply Now</a>
        </div>
        <div class="fixed-deposit-card hidden-card">
            <div class="card-title">Standard</div>
            <div class="card-details">
            <span class="check-icon">&#10003;</span> Take Minimum $1000.00<br>
                <span class="check-icon">&#10003;</span> Take Maximum $5000.00<br>
                <span class="check-icon">&#10003;</span> Per Installment 7%<br>
                <span class="check-icon">&#10003;</span> Installment Interval 30 Days<br>
                <span class="check-icon">&#10003;</span> Total Installment 15<br>
            </div>
            <a href="#" class="apply-button">Apply Now</a>
</div>
</div>




        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll('.fixed-deposit-card');

            function checkScroll() {
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const isVisible = (rect.top >= 0 && rect.bottom <= window.innerHeight);
                    
                    if (isVisible) {
                        card.classList.add('visible-card');
                        card.classList.remove('hidden-card');
                    } else {
                        card.classList.add('hidden-card');
                        card.classList.remove('visible-card');
                    }
                });
            }

            window.addEventListener('scroll', checkScroll);
            checkScroll(); // Initial check
        });
    </script>