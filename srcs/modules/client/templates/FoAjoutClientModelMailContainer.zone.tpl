			{if $bEdit}
				{for $i=0; $i<sizeof($toModelMail); $i++}
					{if $toModelMail[$i]->modelmail_id == 1}{assign $zContent = 'content_mail_auto'}{/if}
					{if $toModelMail[$i]->modelmail_id == 2}{assign $zContent = 'content_mail_relance'}{/if}
					{if $toModelMail[$i]->modelmail_id == 3}{assign $zContent = 'content_mail_changeprof'}{/if}
					{if $toModelMail[$i]->modelmail_id == 4}{assign $zContent = 'content_mail_perso'}{/if}
					<p class="clear">
						<label>
							<input type="checkbox" title="{$toModelMail[$i]->modelmail_label}" id="{$toModelMail[$i]->modelmail_ident}" name="{$toModelMail[$i]->modelmail_ident}" value="{$toModelMail[$i]->modelmail_value}" onclick="checkThis({$toModelMail[$i]->modelmail_value}, this.checked);">
						</label>
						<span style="font-size:1.2em;font-weight: bold;">
							{$toModelMail[$i]->modelmail_label}
						</span>
						<br />
						<br />
						<label style="font-size:1.2em;font-weight: bold;">
							Objet
						</label>
						<span style="font-size:1.2em;font-weight: bold;">
							<textarea name="objet_{$toModelMail[$i]->modelmail_ident}">{$toModelMail[$i]->modelmail_objet}</textarea>
						</span>
						<br />
						<br />
						<span style="font-size:1.2em;font-weight: bold;">{fckeditor $zContent, 'Default', '100%', 200, $toModelMail[$i]->modelmail_content}</span>
					</p>
				{/for}
			{/if}