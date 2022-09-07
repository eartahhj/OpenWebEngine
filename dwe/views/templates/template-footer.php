<?php
    if (!function_exists('templateEnd' . ucfirst($_template->getName()))) {
        $_messages->add(ALERT_TYPE_ERROR, _('Error loading template: Footer not configured'));
        $_template->exit();
    }
    if (!call_user_func('templateEnd' . ucfirst($_template->getName()))) {
        $_messages->add(ALERT_TYPE_ERROR, _('Error loading template: Footer could not be loaded'));
        $_template->exit();
    }

    $_template->setClosed(true);
    ?>

    <footer>
        <div id="footer-top">
            <div class="container">
                <div class="grid">
                    <div class="grid-col">
                        <h2>differentWebEngine</h2>
                        <nav id="nav-lang">
                            <p><?=($_language == 'it' ? 'Switch to ' : "Passa all'")?><a href="/<?=($_language == 'it' ? 'en' : 'it')?>"><?=($_language == 'it' ? 'English' : 'Italiano')?></a></p>
                        </nav>
                        <p>Email: <a href="mailto:yourmail@domain.com">yourmail@domain.com</a></p>
                        <nav id="nav-social">
                            <ul>
                                <li class="liberapay">
                                    <a href="https://liberapay.com/GamingHouse/donate" rel="nofollow noopener external" target="_blank">
                                        <span class="icon"></span>Donate
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="grid-col">
                        <p class="h4"><?=_('Info')?></p>
                        <nav>
                            <ul>
                                <li><a href="#"><?=_('Privacy policy')?></a></li>
                                <li><a href="#"><?=_('Cookie policy')?></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-bottom">
            <div class="container">
                <h3>made by <a href="https://github.com/eartahhj" rel="external noopener" target="_blank">eartahhj</a> with <a href="https://github.com/eartahhj"  rel="external noopener" target="_blank">differentWebEngine</a>, an open source framework.</h3>
            </div>
        </div>
    </footer>
</body>
</html>
