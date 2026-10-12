<?php
$submissionStatus = "";
$referenceId = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $grantType = htmlspecialchars(trim($_POST['grantType'] ?? ''));
    $amount = htmlspecialchars(trim($_POST['amount'] ?? ''));
    $fulfillment = htmlspecialchars(trim($_POST['fulfillment'] ?? ''));
    $reason = htmlspecialchars(trim($_POST['reason'] ?? ''));

    if (!empty($fullName) && !empty($email) && !empty($grantType)) {
        // Generate a secure confirmation reference code
        $referenceId = "PTG-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        $submissionStatus = "success";
    } else {
        $submissionStatus = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Grant Application Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-brand-800 text-white flex items-center justify-center font-bold text-xl shadow-md">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-brand-900 tracking-tight">PT GRANT</span>
                    <span class="block text-xs text-slate-500 font-medium">Official Financial Relief Portal</span>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
                <a href="#benefits" class="hover:text-brand-700 transition">Grant Types</a>
                <a href="#notices" class="hover:text-brand-700 transition">Terms & Fees</a>
                <a href="#apply" class="bg-brand-700 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-brand-800 transition">Apply Now</a>
            </div>
        </div>
    </header>

    <section class="relative bg-gradient-to-b from-brand-900 via-brand-800 to-blue-900 text-white py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-6">
                <i class="fa-solid fa-shield-check"></i> 100% Free Application Process
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-6">
                Secure Funding for Homes, Mortgages & Cash Relief
            </h1>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto mb-8 font-normal leading-relaxed">
                Take control of your financial future today. Access verified relief funds designed to help you clear debts, secure housing, and manage essential expenses.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#apply" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    <i class="fa-solid fa-file-lines mr-2"></i> Start Free Application
                </a>
                <a href="#benefits" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold px-8 py-4 rounded-xl transition text-center backdrop-blur-sm">
                    Explore Grant Options
                </a>
            </div>
        </div>
    </section>

    <section id="notices" class="max-w-5xl mx-auto px-4 -mt-8 relative z-20 w-full mb-12">
        <div class="bg-white rounded-2xl shadow-xl border border-amber-100 p-6 sm:p-8 bg-gradient-to-r from-amber-50/70 via-white to-amber-50/30">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Important Guidelines & Fee Disclosure</h2>
                    <ul class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-600 mt-3">
                        <li class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex items-start space-x-3">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                            <div><strong>100% Free Application:</strong> Submitting or processing applications costs nothing.</div>
                        </li>
                        <li class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex items-start space-x-3">
                            <i class="fa-solid fa-truck-fast text-brand-600 mt-1"></i>
                            <div><strong>Doorstep Delivery Fee:</strong> Required only for physical package fulfillment delivered to your door.</div>
                        </li>
                        <li class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex items-start space-x-3">
                            <i class="fa-solid fa-rotate-left text-blue-600 mt-1"></i>
                            <div><strong>Cash Refunds:</strong> Approved cash grants carry flexible refund & verification policies.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="benefits" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Available Grant Categories</h2>
            <p class="text-slate-600 mt-2">Select the specific assistance program that matches your immediate needs.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Home Assistance</h3>
                    <p class="text-sm text-slate-600 mb-4">Grants to help secure housing, cover down payments, or handle critical home repairs.</p>
                </div>
                <button type="button" onclick="selectGrant('Home Assistance Grant')" class="w-full bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-700 text-sm font-semibold py-2.5 rounded-lg transition">Select Option</button>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Mortgage Payoff</h3>
                    <p class="text-sm text-slate-600 mb-4">Financial relief programs targeted towards clearing outstanding mortgage balances.</p>
                </div>
                <button type="button" onclick="selectGrant('Mortgage Payoff Help')" class="w-full bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-700 text-sm font-semibold py-2.5 rounded-lg transition">Select Option</button>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Loan Assistance</h3>
                    <p class="text-sm text-slate-600 mb-4">Support for paying off high-interest personal loans, debts, and financial burdens.</p>
                </div>
                <button type="button" onclick="selectGrant('Other Loan Assistance')" class="w-full bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-700 text-sm font-semibold py-2.5 rounded-lg transition">Select Option</button>
            </div>
            <!-- Card 4 -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Cash Grant Help</h3>
                    <p class="text-sm text-slate-600 mb-4">Direct cash relief awards that can be refunded or disbursed based on criteria checks.</p>
                </div>
                <button type="button" onclick="selectGrant('Cash Grant Help')" class="w-full bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-700 text-sm font-semibold py-2.5 rounded-lg transition">Select Option</button>
            </div>
        </div>
    </section>

    <section id="apply" class="max-w-3xl mx-auto px-4 py-12 w-full flex-grow">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8 sm:p-10">
            <div class="text-center mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-blue-50 px-3 py-1 rounded-full">Secure Form</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">PT Grant Application Form</h2>
                <p class="text-slate-500 text-sm mt-1">Please fill in your details accurately to initiate your review.</p>
            </div>

            <?php if ($submissionStatus === "success"): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-6 rounded-2xl mb-6 text-center shadow-sm">
                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xl mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="font-bold text-lg">Application Successfully Submitted!</h3>
                    <p class="text-sm text-emerald-700 mt-1">
                        Thank you, <strong><?php echo $fullName; ?></strong>! Your application for <strong><?php echo $grantType; ?></strong> has been received.<br>
                        Your Reference ID is <strong><?php echo $referenceId; ?></strong>. We will contact you via email shortly.
                    </p>
                    <a href="index.php" class="inline-block mt-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">Submit Another Application</a>
                </div>
            <?php else: ?>
                <?php if ($submissionStatus === "error"): ?>
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl mb-6 text-center text-sm">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Please fill in all required fields to proceed with your application.
                    </div>
                <?php endif; ?>

                <form id="grantForm" action="index.php#apply" method="POST" onsubmit="return validateForm(event)" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Full Legal Name *</label>
                            <input type="text" id="fullName" name="fullName" required placeholder="e.g. John Doe" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="john@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required placeholder="(555) 000-0000" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Select Grant Program *</label>
                            <select id="grantType" name="grantType" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm bg-white">
                                <option value="">-- Choose Program --</option>
                                <option value="Home Assistance Grant">Home Assistance Grant</option>
                                <option value="Mortgage Payoff Help">Mortgage Payoff Help</option>
                                <option value="Other Loan Assistance">Other Loan Assistance</option>
                                <option value="Cash Grant Help">Cash Grant Help</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estimated Amount Needed ($) *</label>
                            <input type="text" id="amount" name="amount" required placeholder="e.g. $10,000" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Fulfillment Preference</label>
                            <select id="fulfillment" name="fulfillment" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm bg-white">
                                <option value="Digital Direct Deposit">Digital Direct Deposit / Transfer</option>
                                <option value="Doorstep Physical Aid">Doorstep Physical Package (Delivery fee applies)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Reason for Application & Financial Background *</label>
                        <textarea id="reason" name="reason" required rows="4" placeholder="Briefly describe your current financial situation and how this grant will help..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-transparent outline-none transition text-sm"></textarea>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-500 flex items-center space-x-3">
                        <input type="checkbox" id="termsCheck" required class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <label for="termsCheck">I confirm that all information provided is accurate. I understand applications are free, doorstep delivery fees apply where applicable, and cash grants are subject to verification.</label>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full bg-brand-700 hover:bg-brand-800 text-white font-bold py-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center space-x-2">
                        <span>Submit Free Application</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sm">&copy; 2026 PT Grant Program. All rights reserved. Secure SSL Encrypted Portal.</p>
        </div>
    </footer>

    <script>
        function selectGrant(grantName) {
            const selectElement = document.getElementById('grantType');
            if (selectElement) {
                selectElement.value = grantName;
            }
            document.getElementById('apply').scrollIntoView({ behavior: 'smooth' });
        }

        function validateForm(event) {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const grantType = document.getElementById('grantType').value;
            const termsChecked = document.getElementById('termsCheck').checked;

            if (!fullName || !email || !phone || !grantType) {
                alert('Please complete all required fields.');
                return false;
            }

            if (!termsChecked) {
                alert('You must agree to the terms regarding free application and delivery fees.');
                return false;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing Application...`;
            return true;
        }
    </script>
</body>
</html>
