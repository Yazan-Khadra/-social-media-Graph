<form id="otpForm">
    <input type="text" name="otp" placeholder="Enter OTP" maxlength="6" required>
    <button type="submit">Verify</button>
</form>

<div id="error-box" class="error-box" style="display:none;"></div>
<div id="success-box" class="success-box" style="display:none;"></div>

<script>
document.getElementById('otpForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const otp = this.otp.value;
    const errorBox = document.getElementById('error-box');
    const successBox = document.getElementById('success-box');

    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    try {
        const response = await fetch('http://127.0.0.1:8000/api/email/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token') // توكن المستخدم
            },
            body: JSON.stringify({ otp })
        });

        const data = await response.json();

        if(response.ok){
            successBox.style.display = 'block';
            successBox.textContent = data.message;
        } else {
            errorBox.style.display = 'block';
            errorBox.textContent = data.message;
        }

    } catch(err){
        errorBox.style.display = 'block';
        errorBox.textContent = 'Something went wrong';
    }
});
</script>
