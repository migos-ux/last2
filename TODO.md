# Fix CSRF Token Invalid Error

## Issues Identified
- CSRF validation throws uncaught exception causing fatal error
- No exception handling in Router.php
- CSRF token not regenerated after successful auth
- Potential session configuration issues
- Other forms may lack CSRF protection

## Plan
- [x] Add exception handling in Router.php to catch CSRF exceptions and redirect gracefully
- [x] Regenerate CSRF token after successful login/register/logout
- [x] Ensure session path is writable
- [x] Add CSRF validation to other POST forms (Cart, Admin, API)
- [x] Test the fixes

## Files to Edit
- core/Router.php: Add try-catch in callAction
- controllers/AuthController.php: Regenerate CSRF after auth
- core/Controller.php: Ensure CSRF generation is secure
- Check other controllers for POST methods
