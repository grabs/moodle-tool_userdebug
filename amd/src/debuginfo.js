// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Tool userdebug print a debug infobox
 *
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import templates from 'core/templates';
import notification from 'core/notification';

/**
 * Initialize a nice infobox to show the user, the user debug mode is active.
 * @param {object} infocontent This parameter is used as content for the template we want to render.
 */
export const init = (infocontent) => {
    // Create the infobox container, which is a simple div element.
    const banner = document.createElement('div');

    // The mustache template we use for the infobox content.
    const tmpl = 'tool_userdebug/debuginfo';

    templates.render(tmpl, infocontent).then(function(html, js) {
        banner.innerHTML = html;
        banner.classList.add(
            'tool-userdebug-infobox',
            'border',
            'rounded',
            'bg-warning'
        );
        document.body.appendChild(banner);
        if (js) {
            templates.runTemplateJS(js);
        }
        // Make the box transparent but not invisible.
        setTimeout(() => {
            banner.classList.add('transparent');
        }, 5000);
        return;
    }).fail(function(ex) {
        if (ex.debuginfo) {
            notification.exception(ex);
        }
    });
};
