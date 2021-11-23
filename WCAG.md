# [Web Content Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

## [Windows Narrator screen reader](https://support.microsoft.com/fi-fi/windows/lukijan-t%C3%A4ydellinen-opas-e4397a0d-ef4f-b386-d8ae-c172f109bdb1#WindowsVersion=Windows_10)

- start: WIN + CTRL + Enter 
- Reader button: Caps Lock
- Hyper links and image descriptions: Reader + CTRL + D
- Summary: Reader + S

## 1.4.1 Use of color

- Contrast ratio of 3:1 with surrounding text and providing additional visual cues on focus for links.

## 1.4.3 Contrast

Example [contrast checker.](https://webaim.org/resources/contrastchecker/)

Between text and background behind the text:

- At least 4.5:1
- Large text > 3:1

Large tex: >18pt or >14pt bold

- 14pt = 1.1667rem ~ 19px
- 18pt ~ 24px

Contrast ratio of 3:1 is needed with surrounding text. But color alone is not enough to convey information so additional visual cue on focus like underline or font change etc. is needed for links and controls.

## Alt-text

- Decorative images have empty alt-text or image is CSS background image.
- Images that are links need to have text alternative describing the target.

## Link vs. Button

A link can be triggered with the enter key but not with space. It will redirect you to a new page or a section within the same page.

A button can be triggered with the space or enter key. It will trigger an action like opening or closing something, or send a form.
