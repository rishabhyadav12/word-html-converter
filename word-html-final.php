<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert Word to HTML</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <style>

    body{
        font-family:'Poppins', sans-serif;
    }

        #output {
            margin-top: 15px;
            border: 1px solid #ccc;
            padding: 10px;
            max-width: 100%;
            word-wrap: break-word;
            background-color: #f9f9f9;
            user-select: text;
            min-height: 200px;
            white-space: pre-wrap;
            position: relative;
            font-family:"Poppins", serif;
            font-size:14px !important;
            color:#151515;
        }

        .block-element {
            display: block;
            margin-bottom: 10px;
        }
 
        .copy-button {
            position: absolute;
            background-color:rgb(14, 14, 14);
            color: white;
            padding: 7px 15px;
            border: none;
            cursor: pointer;
            display: none;
            border-radius:4px
        }

        .copy-button.visible {
            display: inline-block;
            z-index: 10;
        }

        ul, ol {
            margin: 0;
            padding-left: 20px;
        }

        /* The Popup Modal */
.popup {
    display: none;
    position: fixed;
    z-index: 10;
    top: 2%;
    left: 10px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 50%;
    max-width: 600px;
    padding: 6px 2px 2px;
    height:550px;
    overflow-y:scroll
}

/* Popup Content */
.popup-content {
    position: relative;
    padding: 10px;
}

/* The Close Button */
.close {
    color: #000;
    font-size: 29px;
    font-weight: 500;
    position: absolute;
    top: -12px;
    right: 15px;
    cursor: pointer;

}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

::-webkit-scrollbar {
    width: 6px;  
    height: 6px;
}

::-webkit-scrollbar-thumb {
    background-color: #888; 
    border-radius: 10px; 
}

::-webkit-scrollbar-track {
    background-color: #f1f1f1;  
    border-radius: 10px; 
}

::-webkit-scrollbar-thumb:hover {
    background-color: #555; 
}


.show-output{
    background-color:#4169e1;
    outline:none;
    border:none;
    color:#fff;
    padding:2px 8px;
    border-radius:2px;
    font-family:'Poppins', sans-serif;
    cursor: pointer;
    font-size:13px;
}

input[type="file"]::file-selector-button {
  border: 2px solid #6c5ce7;
  padding: 0.2em 0.4em;
  border-radius: 0.2em;
  background-color:rgb(234, 233, 255);
  transition: 1s;
}

input[type="file"]::file-selector-button:hover {
  background-color:rgb(232, 255, 255);
  border: 2px solid #00cec9;
}

    </style>
</head>
<body>
<div style="width:45%;float:left">

<h1>Word to HTML Converter</h1>

    <input type="file" id="upload" name="upload" accept=".docx, .doc"  />
  
   <button id="openPopupBtn" class="show-output">Show Output</button>

<div id="popupModal" class="popup">
  <div class="popup-content">
    <span class="close">&times;</span>
    <div id="output"></div> 
  </div>
</div>
</div>

</div>

</div>

    <script>
        document.getElementById('upload').addEventListener('change', function(event) {
            var reader = new FileReader();

            reader.onload = function(event) {
                var arrayBuffer = reader.result;

                // Use Mammoth.js to convert the .docx to HTML
                mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                    .then(function(result) {
                        var htmlContent = result.value;

                        // Clean up the HTML content by replacing multiple spaces with one
                        htmlContent = removeMultipleSpaces(htmlContent);

                        // Insert the resulting HTML into the output div
                        document.getElementById('output').innerHTML = htmlContent;
                    })
                    .catch(function(err) {
                        alert("Error converting document:", err);
                    });
            };

            // Read the file as an ArrayBuffer (this will allow us to process .docx files)
            reader.readAsArrayBuffer(event.target.files[0]);
        });

        // Function to replace multiple spaces with a single space
        function removeMultipleSpaces(html) {
            // This regex will match two or more spaces and replace them with a single space
            return html.replace(/\s{2,}/g, ' ');
        }
        
        // Function to copy HTML content to clipboard
    function copyContentToClipboard(content) {
    // Format the HTML content to ensure it's properly separated with newlines
    content = content.replace(/<\/(h1|h2|h3|p|ul|ol|li)>/g, "</$1>\n");  // Add newline after block-level elements
    content = content.replace(/<br><br>/g, "<br>");  // Optional: fix double <br> tags
    
    // Create a temporary textarea to copy the content
    var textArea = document.createElement('textarea');
    textArea.value = content;  // Set the HTML content as the value of the textarea
    textArea.style.position = 'absolute';
    textArea.style.opacity = '0';  // Hide the textarea
    document.body.appendChild(textArea);

    // Select the content in the textarea
    textArea.select();
    textArea.setSelectionRange(0, 99999); // For mobile devices

    // Execute the "copy" command to copy the HTML content to the clipboard
    document.execCommand('copy');

    // Remove the temporary textarea element
    document.body.removeChild(textArea);
}

document.addEventListener('mouseup', function(event) {
    var selection = window.getSelection();
    var outputElement = document.getElementById('output');  // Reference to the element with ID "output"

    // Check if there is a selection and if the selection is inside the "output" element
    if (selection.rangeCount > 0 && outputElement.contains(selection.anchorNode)) {
        var range = selection.getRangeAt(0);  // Get the selected range
        var selectedHTML = range.cloneContents();

        var htmlContent = '';

        // Check if the selection is a single element (e.g., <h1>, <p>, etc.)
        if (selection.anchorNode === selection.focusNode && selection.anchorNode.nodeType === 1) {
            var selectedNode = selection.anchorNode;

            // Skip empty <h3>, <p>, <h2> or any other empty block-level elements
            if ((selectedNode.tagName === 'P' || selectedNode.tagName === 'H2' || selectedNode.tagName === 'H3') && selectedNode.textContent.trim() === '') {
                htmlContent = '';  // Don't copy empty tags
            } else {
                htmlContent = selectedNode.outerHTML;  // Use outerHTML for single element
            }
        } else {
            // Multiple elements selected
            selectedHTML.childNodes.forEach(function(node) {
                // Check if the node is an element and is not empty
                if (node.nodeType === 1 && node.textContent.trim() !== '') {  // Only process non-empty element nodes
                    htmlContent += node.outerHTML;
                }
            });
        }

        // Create the copy button if it doesn't exist
        var copyButton = document.querySelector('.copy-button');
        if (!copyButton) {
            copyButton = document.createElement('button');
            copyButton.classList.add('copy-button');

            // Create the image element
            var copyImage = document.createElement('img');
            copyImage.src = 'copy.png';  // Path to your image
            copyImage.alt = 'Copy';  // Alt text for accessibility
            copyImage.style.marginRight = '8px';  // Optional: adds space between image and text
            copyImage.style.verticalAlign = 'middle';
            // Append the image to the button, followed by the text
            copyButton.appendChild(copyImage);
            copyButton.appendChild(document.createTextNode('Copy'));

            document.body.appendChild(copyButton);
        }

        // Position the button near the selection
        var rect = selection.getRangeAt(0).getBoundingClientRect();
        copyButton.style.top = rect.bottom + window.scrollY + 10 + 'px';  // Move button to the bottom of selection
        copyButton.style.left = rect.left + window.scrollX + 60 + 'px'; 

        // Show the button when text is selected
        copyButton.classList.add('visible');

        // Reset the button to "Copy" text and image every time a new selection is made
        copyButton.textContent = ''; // Clear any previous text ("Copied!")
        
        // Add the image and text again
        var copyImage = document.createElement('img');
        copyImage.src = 'copy.png';
        copyImage.alt = 'Copy';
        copyImage.style.marginRight = '8px';
        copyImage.style.verticalAlign = 'middle';
        copyButton.appendChild(copyImage);
        copyButton.appendChild(document.createTextNode('Copy'));
        copyButton.style.backgroundColor = '';  // Reset background color to default

        // Add click event to copy the content when clicked
        copyButton.onclick = function() {
            if (htmlContent) {
                // Copy the formatted HTML content to clipboard
                copyContentToClipboard(htmlContent);

                // Change the button text to "Copied!"
                copyButton.textContent = 'Copied!';
                copyButton.style.backgroundColor = 'green';  // Optional: change the background color of the button

                // Reset the button text after a short delay
                setTimeout(function() {
                    copyButton.textContent = ''; // Reset the text content
                    // Append the image again if needed
                    copyButton.appendChild(copyImage);
                    copyButton.appendChild(document.createTextNode('Copy'));
                    copyButton.style.backgroundColor = '';  // Reset background color
                }, 400); 

                // Hide the button after copying
                setTimeout(function() {
                    copyButton.classList.remove('visible');
                }, 400); 
            }
        };

    } else {
        // Hide the button if selection is outside the "output" element
        var copyButton = document.querySelector('.copy-button');
        if (copyButton) {
            copyButton.classList.remove('visible');
        }
    }
});


// Function to listen for Ctrl + C keypress and copy HTML content with tags
document.addEventListener('keydown', function(event) {
    // Listen for Ctrl + C (without interfering with regular copy operation)
    if (event.ctrlKey && event.key === 'c') {
        var selection = window.getSelection();

        if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);  // Get the selected range
            var htmlContent = '';

            // Check if the selection is inside a specific container, e.g., the "output" element
            var outputElement = document.getElementById('output');
            if (!outputElement.contains(selection.anchorNode)) {
                return;  // If the selection is outside the "output" element, do nothing
            }

            // Check if the selection is a single element (e.g., <h1>, <p>, etc.)
            if (selection.anchorNode === selection.focusNode && selection.anchorNode.nodeType === 1) {
                var selectedNode = selection.anchorNode;

                // Skip empty <h3>, <p>, <h2>, or any other empty block-level elements
                if ((selectedNode.tagName === 'P' || selectedNode.tagName === 'H2' || selectedNode.tagName === 'H3') && selectedNode.textContent.trim() === '') {
                    htmlContent = '';  // Don't copy empty tags
                } else {
                    htmlContent = selectedNode.outerHTML;  // Use outerHTML for single element
                }
            } else {
                // Multiple elements selected
                var selectedHTML = range.cloneContents();
                selectedHTML.childNodes.forEach(function(node) {
                    // Check if the node is an element and is not empty
                    if (node.nodeType === 1 && node.textContent.trim() !== '') {  // Only process non-empty element nodes
                        htmlContent += node.outerHTML;
                    }
                });
            }

            // If there's HTML content, copy it to the clipboard
            if (htmlContent) {
                copyContentToClipboard(htmlContent);

                // Provide user feedback that the content has been copied
                showCopyFeedback();
                
                // Hide the copy button after copying content using Ctrl+C
                hideCopyButton();
            }

            event.preventDefault();  // Prevent the default Ctrl+C action (copy text only)
        }
    }
});


// Function to show feedback (e.g., a small notification)
function showCopyFeedback() {
    var feedback = document.createElement('div');
    feedback.textContent = 'Content copied to clipboard!';
    feedback.style.position = 'fixed';
    feedback.style.bottom = '20px';
    feedback.style.left = '50%';
    feedback.style.transform = 'translateX(-50%)';
    feedback.style.backgroundColor = 'green';
    feedback.style.color = 'white';
    feedback.style.padding = '10px';
    feedback.style.borderRadius = '5px';
    feedback.style.fontSize = '12px';
    feedback.style.zIndex = '9999';

    // Append feedback and remove after a short duration
    document.body.appendChild(feedback);
    setTimeout(function() {
        document.body.removeChild(feedback);
    }, 1000);
}

// Function to hide the copy button
function hideCopyButton() {
    var copyButton = document.querySelector('.copy-button');
    if (copyButton) {
        copyButton.classList.remove('visible');
    }
}

// Get the modal and button elements
var modal = document.getElementById("popupModal");
var btn = document.getElementById("openPopupBtn");
var span = document.getElementsByClassName("close")[0];

// Function to open the popup and set content
function openPopup() {
    // Example of setting content for the output div
    document.getElementById('output');

    // Display the modal
    modal.style.display = "block";
}

// Function to close the popup
span.onclick = function() {
    modal.style.display = "none";
}

// Bind the openPopup function to the button click
btn.onclick = openPopup;

    </script>
</body>
</html>
