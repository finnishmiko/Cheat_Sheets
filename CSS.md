# CSS

## Owerflow, hyphens

The white-space CSS property for white space inside an element.

The owerflow-wrap to break words.


Hidden `&shy;` (soft hyphen) can be used. It is not visible in the text but it can be used to hint browser where to break the word.

<wbr>: The Line Break Opportunity element.

## Customize scroll bar.
Note that this doesn't work in iPhone.

```
::-webkit-scrollbar {
    -webkit-appearance: none;
}

::-webkit-scrollbar:vertical {
    width: 30px;
}

::-webkit-scrollbar:horizontal {
    height: 12px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .6);
    border-radius: 10px;
    border: 2px solid #474747;
}

::-webkit-scrollbar-track {
    // border-radius: 10px;
    background-color: #c8c8c8;
}
```
