<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2014 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/
?>

<?php
/**
 * @file templates/tmpl_preferences.php
 * Dialog to change user preferences, to be included in the clouds preferences page.
 * @access public
 * @author Christian Reiner
 */
?>

<fieldset id="shorty-fieldset" class="personalblock">
	<form id="shorty-preferences">
<?php require_once('tmpl_wdg_shortlet.php'); ?>
			<p>
				<!-- default-status -->
				<label for="status" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Status").":"); ?></label>
				<span id="status" style="margin-right:1em;">
					<select id="default-status" name="default-status" data="<?php echo $_['default-status'];?>">
<?php
						foreach ( OC_Shorty_Type::$STATUS as $status )
							if ( 'deleted'!=$status )
								print_unescaped(sprintf("<option value=\"%s\" %s>%s</option>\n",
														$status, ($_['default-status']==$status)?'selected':'', OC_Shorty_L10n::t($status)));
?>
					</select>
					<em><?php p(OC_Shorty_L10n::t("The default status as suggested for new Shortys.")); ?></em>
				</span>
			</p>
			<p>
				<!-- backend selection -->
				<label for="backend-type" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Backend").":"); ?></label>
				<!-- list of available backend types -->
				<span style="margin-right:1em;">
					<select id="backend-type" name="backend-type" style="width:13em;"
							placeholder="<?php p(OC_Shorty_L10n::t("Choose service…")); ?>" >
						<?php
							foreach ( $_['backend-types'] as $value=>$display )
								print_unescaped(sprintf ( "        <option value=\"%s\" %s>%s</option>\n",
												$value,
												($value==$_['backend-type']?'selected':''),
												OC_Shorty_L10n::t($display)));
						?>
					</select>
					<em><?php p(OC_Shorty_L10n::t("The backend service used to generate shortened URLs.")); ?></em>
				</span>
				<!-- some additional fields: input, explanation and example -->
				<!-- depending on the chosen backend-type above only one of the following span tags will be displayed -->
				<span id="backend-none" class="backend-supplement" style="display:none;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://%s%s<em>&lt;shorty id&gt;</em>',$_SERVER['SERVER_NAME'],OCP\Util::linkTo('shorty','',false))); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php print_unescaped(sprintf('%s<br />%s<br />%s',
							OC_Shorty_L10n::t("No backend is used, direct links pointing to your ownCloud are generated."),
							OC_Shorty_L10n::t("Such links are most likely longer than those generated when using a backend."),
							OC_Shorty_L10n::t("However this option does not rely on any third party service and keeps your shortys under your control."))); ?>
					</span>
				</span>
				<!-- backend -static- -->
				<span id="backend-static" class="backend-supplement" style="display:none;">
					<input id="backend-static-base" type="hidden" name="backend-static-base" value="<?php p($_['backend-static-base']); ?>">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<a id="example" class="shorty-example" title="<?php p(OC_Shorty_L10n::t("Verify by clicking…")); ?>">
							<?php print_unescaped(sprintf('http://%s/<em>&lt;service&gt;</em>/<em>&lt;shorty id&gt;</em>',$_SERVER['SERVER_NAME'])); ?>
						</a>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php print_unescaped(sprintf("%s<br />\n%s<br />\n%s<br />\n%s",
							OC_Shorty_L10n::t("Static, rule-based backend, generates shorty links relative to a given base url."),
							OC_Shorty_L10n::t("Since this setup depends on server based configuration rules the base url can only be specified in the 'Admin' section of the configuration."),
							OC_Shorty_L10n::t("Have a try with the example link provided, click it, it should result in a confirmation that your setup is working."),
							OC_Shorty_L10n::t("Only use this backend, if you can provide a short base url that is mapped the described way. Your shorties won't work otherwise."))); ?>
					</span>
				</span>
				<!-- backend bit.ly -->
				<span id="backend-bitly" class="backend-supplement" style="display:none;">
					<label for="backend-bitly-user" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("bit.ly user").':'); ?></label>
					<input id="backend-bitly-user" type="text" name="backend-bitly-user" value="<?php p($_['backend-bitly-user']); ?>"
						maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t("bit.ly user name")); ?>" style="width:10em;">
					<label for="backend-bitly-key" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("bit.ly key").':'); ?></label>
					<input id="backend-bitly-key" type="text" name="backend-bitly-key" value="<?php p($_['backend-bitly-key']); ?>"
						maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t("bit.ly users key")); ?>" style="width:18em;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
					<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
					<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://bitly.com/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php print_unescaped(sprintf("%s<br />\n%s<br />\n%s",
							OC_Shorty_L10n::t("The external 'bitly.com' service is used to register a short url for each generated shorty."),
							OC_Shorty_L10n::t("The service requires you to authenticate yourself by providing a valid bit.ly user name and an '%s'.",
								sprintf('<a class="external" href="http://bitly.com/a/your_api_key" target="_blank">%s</a>',OC_Shorty_L10n::t("API access key")) ),
							OC_Shorty_L10n::t("This means you have to '%s' at their site first.",
								sprintf('<a class="external" href="http://bitly.com/a/sign_up" target="_blank">%s</a>',OC_Shorty_L10n::t("register an account")) ))); ?>
					</span>
				</span>
				<!-- backend cligs -->
				<span id="backend-cligs" class="backend-supplement" style="display:none;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://cli.gs/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php p(OC_Shorty_L10n::t("The external 'cli.gs' service is used to register a short url for each generated shorty.")); ?>
					</span>
				</span>
				<!-- backend is.gd -->
				<span id="backend-isgd" class="backend-supplement" style="display:none;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://is.gd/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php p(OC_Shorty_L10n::t("The external 'is.gd' service is used to register a short url for each generated shorty.")); ?>
					</span>
				</span>
				<!-- backend google -->
				<br/>
				<span id="backend-google" class="backend-supplement" style="display:none;">
					<label for="backend-google-key" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("API key").':'); ?></label>
					<input id="backend-google-key" type="text" name="backend-google-key" value="<?php p($_['backend-google-key']); ?>"
						maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t("Google API key")); ?>" style="width:24em;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://goo.gl/<em>&lt;shorty id&gt;</em>')); ?></span>
						<br/>
						<label for="backend-ssl-verify" class="shorty-aspect"></label>
						<span>
							<input id="backend-ssl-verify" type="checkbox" name="backend-ssl-verify" value="1"
								<?php p($_['backend-ssl-verify']?'checked':''); ?> >
							<?php p(OC_Shorty_L10n::t("Force verification of encryption certificates during communication with the backend").'.'); ?>
						</span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php print_unescaped(sprintf("%s<br />\n%s<br />\n%s<br />\n%s",
							OC_Shorty_L10n::t("Googles external 'goo.gl service' is used to register a short url for each generated shorty."),
							sprintf(OC_Shorty_L10n::t("You must provide a valid '%%s' to use this service."),
								sprintf('<a class="external shorty-clickable" href="https://code.google.com/apis/console/" target="_blank">%s</a>',OC_Shorty_L10n::t("Google API key")) ),
							OC_Shorty_L10n::t("This means you require a 'Google API console account'."),
							sprintf(OC_Shorty_L10n::t("Register a new '%%s' at their pages."),
								sprintf('<a class="external shorty-clickable" href="https://code.google.com/apis/console/" target="_blank">%s</a>',OC_Shorty_L10n::t("Google API account")) )));?>
					</span>
				</span>
				<!-- backend tinycc -->
				<span id="backend-tinycc" class="backend-supplement" style="display:none;">
					<label for="backend-tinycc-user" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("tiny.cc user").':'); ?></label>
					<input id="backend-tinycc-user" type="text" name="backend-tinycc-user" value="<?php p($_['backend-tinycc-user']); ?>"
						maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t("tiny.cc user name")); ?>" style="width:10em;">
					<label for="backend-tinycc-key" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("tiny.cc key").':'); ?></label>
					<input id="backend-tinycc-key" type="text" name="backend-tinycc-key" value="<?php p($_['backend-tinycc-key']); ?>"
						maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t("tiny.cc user key")); ?>" style="width:19em;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://tiny.cc/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php print_unescaped(sprintf ( "%s<br />\n%s<br />\n%s",
							OC_Shorty_L10n::t("The external 'tiny.cc' service is used to register a short url for each generated shorty."),
							OC_Shorty_L10n::t("The service requires you to authenticate yourself by providing a valid tiny.cc user name and an api access key."),
							OC_Shorty_L10n::t("This means you have to register an '%s' at their site first.", array (
								sprintf('<a class="external" href="http://tiny.ccc/" target="_blank">%s</a>', OC_Shorty_L10n::t("account") ) ) ))); ?>
					</span>
				</span>
				<!-- backend tinyURL -->
				<span id="backend-tinyurl" class="backend-supplement" style="display:none;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
						<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://ti.ny/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php p(OC_Shorty_L10n::t("The external 'ti.ny' service is used to register a short url for each generated shorty.")); ?>
					</span>
				</span>
				<!-- backend turl -->
				<span id="backend-turl" class="backend-supplement" style="display:none;">
					<br/>
					<label for="backend-example" class="shorty-aspect"> </label>
					<span id="backend-example">
						<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").':'); ?></label>
					<span id="example" class="shorty-example"><?php print_unescaped(sprintf('http://turl.ca/<em>&lt;shorty id&gt;</em>')); ?></span>
					</span>
					<br/>
					<span id="explain" class="shorty-explain">
						<?php p(OC_Shorty_L10n::t("The external 'turl' service is used to register a short url for each generated shorty.")); ?>
					</span>
				</span>
			</p>
			<p>
				<!-- sms -->
				<label for="sms" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("SMS").":"); ?></label>
				<span id="sms" style="margin-right:1em;">
					<select id="sms-control" name="sms-control" style="width:11em;">
						<?php print_unescaped(sprintf("<option value=\"disabled\" %s>%s</option>\n",
											('enabled'!=$_['sms-control']?'selected':''),
											OC_Shorty_L10n::t('disabled'))); ?>
						<?php print_unescaped(sprintf("<option value=\"enabled\" %s>%s</option>\n",
											('enabled'==$_['sms-control']?'selected':''),
											OC_Shorty_L10n::t('enabled'))); ?>
					</select>
					<em><?php p(OC_Shorty_L10n::t("Enabling the SMS option will offer sending a Shorty via SMS.")); ?></em>
				</span>
			</p>
			<p>
				<span class="shorty-explain"><em><?php print_unescaped(OC_Shorty_L10n::t("Unfortunately support for 'SMS url handling' is usually only found on mobile devices like smart phones.")."<br>\n");
												print_unescaped(OC_Shorty_L10n::t("In addition, those implementations are minimalistic, buggy and differ from system to system.")."<br>\n");
												print_unescaped(OC_Shorty_L10n::t("In short: this might not work for you, therefore you can disable it…")."<br>\n"); ?></em></span>
			</p>
			<p>
				<!-- verbosity -->
				<label for="verbosity" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Feedback").":"); ?></label>
				<span id="verbosity" style="margin-right:1em;">
					<select id="verbosity-control" name="verbosity-control" style="width:11em;">
						<?php foreach (array('none','error','info','debug') as $verbosity)
								print_unescaped(sprintf('<option value="%1$s" %2$s>%3$s</option>'."\n",
											$verbosity,
											($verbosity==$_['verbosity-control']?'selected':''),
											OC_Shorty_L10n::t($verbosity)));
						?>
					</select>
					<em><?php p(OC_Shorty_L10n::t("The amount of feedback messages shown.")); ?></em>
				</span>
				<br />
				<label for="timeout" class="shorty-aspect"></label>
				<span id="timeout" style="margin-right:1em;">
					<select id="verbosity-timeout" name="verbosity-timeout" style="width:11em;">
						<?php foreach (array('(never)'=>0, '2 seconds'=>2000, '5 seconds'=>5000, '12 seconds'=>120000, '1 minute'=>60000) as $key=>$timeout)
								print_unescaped(sprintf('<option value="%1$s" %2$s>%3$s</option>'."\n",
											$timeout,
											($timeout==$_['verbosity-timeout']?'selected':''),
											OC_Shorty_L10n::t($key)));
						?>
					</select>
					<em><?php p(OC_Shorty_L10n::t("The time span after which messages are hidden again automatically.")); ?></em>
				</span>
			</p>
		</div>
	</form>
</fieldset>
