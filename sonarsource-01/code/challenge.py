from django.contrib import messages
from django.shortcuts import render, redirect
from django.contrib.sites.shortcuts import get_current_site
from django.template.loader import render_to_string
from django.utils.http import urlsafe_base64_encode, urlsafe_base64_decode
from django.utils.encoding import force_bytes, force_text
from django.views.decorators.http import require_http_methods
from hashlib import sha1
from project.decorators import check_recaptcha
from project.forms import UserSignUpForm
from project.settings import config
from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail
from django.contrib.auth import get_user_model
User = get_user_model()

@check_recaptcha
@require_http_methods(["POST"])
def register(request):
    form = UserSignUpForm(request.POST)
    if form.is_valid() and request.recaptcha_is_valid:
        user = form.save(commit=False)
        user.is_active = False
        user.save()
        message = render_to_string('mail/activate.html', {
            'user': user,
            'uid': urlsafe_base64_encode(force_bytes(user.pk)),
            'token': sha1(force_bytes(user.pk)).hexdigest(),
        })
        message = Mail(
            from_email='noreply@' + get_current_site(request).domain,
            to_emails=request.POST.get('email'),
            subject='Your account activation email',
            html_content=message)
        response = SendGridAPIClient(config['SG_API_KEY']).send(message)
        messages.add_message(request, messages.SUCCESS, 'Verification email sent.')
    else:
        return render(request, 'account/register.html', {'form': form})
    return render(request, 'account/register.html', {'form': UserSignUpForm()})
