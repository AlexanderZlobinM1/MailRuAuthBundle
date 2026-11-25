# Compatibility index

Last synchronized from confirmed workspace evidence: 25 November 2026.

Current release: `1.1.5` (`8abe37a`). Declared support: Mautic 5.x, 6.x, 7.x; PHP >=8.1.

| Mautic | Status | Confirmed scope |
| --- | --- | --- |
| 5.2.10 | ✓ | Fresh container and integration settings, 6 Sep 2026 |
| 6.0.9 | ✓ | Fresh container and integration settings, 6 Sep 2026 |
| 7.1.3 | ✓ | Fresh container and integration settings, 6 Sep 2026 |
| 7.2.0 | ✓ | Fresh container and integration settings, 6 Sep 2026 |

Mail.ru OAuth exchange was not exercised. The login-page alternative-button
DOM contract change and Serbian translations do not change the runtime
compatibility scope. Update this file and the workspace `../../COMPATIBILITY_INDEX.md`
row with every plugin-related change or review.

The shared-auth wrapper/coordinator passed joint Mautic 7.2.1 acceptance with
Google, Yandex and Passkey on 25 November 2026: one localized separator was
rendered for all four buttons. Mail.ru OAuth exchange remains outside this
UI-only acceptance.

The 1.1.5 compatibility marks inherit the 6 September 2026 fresh-container
and integration-settings checks recorded for the preceding release; no new
runtime compatibility check was performed for this UI/translation update.
