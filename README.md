# Messaging (block_mensagenscurso)

Adds a block with an ON/OFF toggle that controls the visibility of Moodle messaging interface elements **within a course/module context**.

When disabled, the plugin hides the message UI (messages popover, message user button, and header button group).  

---

## Requirements

- Moodle 5.0+.

---

## Installation

1. Copy this folder to: `blocks/mensagenscurso`
2. Visit **Site administration → Notifications**
3. Add the block to a course page (or module page) as a user with permission to add blocks.

---

## Features

- Per-course toggle stored in plugin config (`block_mensagenscurso`, key `course_{courseid}`)
- Toggle button with visual ON/OFF icons (SVG) and status text
- Applies to:
  - Course pages
  - Module pages (including quiz and forum formats enabled by the block)
- When **inactive**, hides these elements (theme/page dependent):
  - Messages popover region (`div.popover-region[data-region='popover-region-messages']`)
  - Message user button (`a#message-user-button`)
  - Header button group (`.btn-group.header-button-group.mx-3`)
- State update via AJAX (`/blocks/mensagenscurso/ajax.php`)

---

## Configuration

There is no global settings page.

The enabled/disabled state is saved per course in Moodle config:

- Component: `block_mensagenscurso`
- Key: `course_{courseid}`
- Value: `1` (active) or `0` (inactive)

---

## Permissions / Capabilities

The block uses Moodle capabilities as follows:

- `block/mensagenscurso:addinstance` — Controls who can see the toggle UI in the block
- `block/mensagenscurso:myaddinstance` — Allow adding the block to Dashboard (My home)

Changing the toggle state via AJAX requires the **core** capability:

- `moodle/course:manageactivities` (checked in the course context)

---

## Data and Privacy (GDPR)

No custom database tables are created.

This plugin does not store personal data. It only stores a per-course state value in Moodle configuration.

### Privacy API

Implements:

- `\core_privacy\local\metadata\null_provider`