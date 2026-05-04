# MailRuAuthBundle

Mail.ru sign-in plugin for Mautic 5, 6, and 7.

The plugin authenticates only existing Mautic users. A Mail.ru login succeeds when:

- the plugin is published and configured,
- Mail.ru returns a valid OAuth authorization code,
- the authorization code is exchanged for an access token with the configured Client ID and Client Secret,
- Mail.ru userinfo returns an email for the authenticated account,
- the optional allowed email domain check passes,
- the Mail.ru email exactly matches an active Mautic user email.

No Mautic user is auto-created.

## Mautic setup

1. Install the bundle into `plugins/MailRuAuthBundle`.
2. Run Mautic plugin discovery/install and clear cache.
3. Open **Settings -> Plugins -> Mail.ru Auth**.
4. Set **Mail.ru Client ID**.
5. Set **Mail.ru Client Secret**.
6. Optionally set **Allowed email domain**, for example `example.com`.
7. Keep **Show Mail.ru button on login page** enabled if the login page should show the extra button.
8. Publish the plugin and save.

The plugin tile shows the exact callback URL and required Mail.ru OAuth scope.

## Mail.ru OAuth setup

This plugin uses the Mail.ru OAuth authorization code flow. The only sensitive value stored in Mautic is the Mail.ru application Client Secret.

### Create or open a Mail.ru OAuth app

1. Open the Mail.ru OAuth application cabinet:
   [oauth.mail.ru/app](https://oauth.mail.ru/app).
2. Create a new application, or open the existing application you want to use.
3. In application settings, add the callback URL shown in the Mail.ru Auth plugin tile. It will look like:

   ```text
   https://mautic.example.com/s/sso_login_check/MailRuAuth
   ```

   Use the exact URL from the plugin tile. Do not replace it with `/s/login` and do not add query strings or fragments.

4. In permissions/scopes, allow user info access. The required scope shown by the plugin is:

   ```text
   userinfo
   ```

5. Save the application.

### Copy values into Mautic

1. In the Mail.ru OAuth application settings, copy the application **Client ID**.
2. Copy **Client Secret**.
3. In Mautic, open **Settings -> Plugins -> Mail.ru Auth**.
4. Paste the values into **Mail.ru Client ID** and **Mail.ru Client Secret**.
5. Save and publish the plugin.
6. Clear Mautic cache if the login page still shows old settings.

### Optional domain restriction

Set **Allowed email domain** only when Mail.ru login should be limited to one mailbox domain, for example:

```text
example.com
```

Leave it empty to allow any Mail.ru account whose email exactly matches an existing Mautic user.

## What happens during login

- The Mautic login page shows a **Sign in with Mail.ru** button.
- The button starts Mautic SSO for `MailRuAuth`.
- Mautic redirects the user to Mail.ru OAuth.
- Mail.ru redirects back to the callback URL with an authorization code.
- Mautic exchanges the code for a Mail.ru access token.
- Mautic requests Mail.ru userinfo and reads `email`.
- If that email exactly matches an existing active Mautic user email, the user is signed in.

## Troubleshooting

- Redirect URI mismatch: add the exact callback URL from the plugin tile to the Mail.ru OAuth application.
- Email is missing: make sure the application has the `userinfo` scope and the Mail.ru account exposes email through userinfo.
- Login returns to the Mautic login page: check that the Mail.ru account email exactly matches an active Mautic user email.
- Domain denied: clear **Allowed email domain** or set it to the mailbox domain used by the Mail.ru account.
- Token verification failed: confirm that the Client ID and Client Secret in Mautic belong to the same Mail.ru OAuth application used for the login.

## References

- [Mail.ru OAuth documentation](https://biz.mail.ru/developer/oauth.html)
