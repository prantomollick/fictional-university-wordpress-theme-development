import React from "react";
import ReactDOM from "react-dom/client";
import "./frontend.scss";

console.log(React.version);

const divsToUpdate = document.querySelectorAll(".paying-attention-update-me");
divsToUpdate.forEach(function (div) {
    const root = ReactDOM.createRoot(div);
    root.render(
        <React.StrictMode>
            <QuizTest />
        </React.StrictMode>
    );

    div.classList.remove("paying-attention-update-me");
});

function QuizTest() {
    return <div>Test</div>;
}
