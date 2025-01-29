import "./index.scss";
import {
    TextControl,
    Flex,
    FlexBlock,
    FlexItem,
    Button,
    Icon,
} from "@wordpress/components";

(function () {
    let locked = false;

    wp.data.subscribe(function () {
        const results = wp.data
            .select("core/block-editor")
            .getBlocks()
            .filter(function (block) {
                return (
                    block.name == "ourplugin/are-you-paying-attention-quiz" &&
                    block.attributes.correctAnswer == undefined
                );
            });

        if (results.length && locked == false) {
            locked = true;
            wp.data.dispatch("core/editor").lockPostSaving("noanswer");
        }

        if (!results.length && locked) {
            locked = false;
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
        }
    });
})();

wp.blocks.registerBlockType("ourplugin/are-you-paying-attention-quiz", {
    title: "Are You Paying Attention Quiz",
    icon: "smiley",
    category: "common",

    attributes: {
        question: { type: "string" },
        answers: { type: "array", default: [""] },
        correctAnswer: { type: "number", default: undefined },
    },

    edit: EditComponent,
    save: function (props) {
        return null;
    },
});

function EditComponent(props) {
    const updateQuestion = (value) => {
        props.setAttributes({ question: value });
    };

    const handleAnswerChange = (value, index) => {
        const newAnswers = [...props.attributes.answers];
        newAnswers[index] = value;
        props.setAttributes({ answers: newAnswers });
    };

    const handleDeleteAnswer = (idxToDelete) => {
        const newAnswers = props.attributes.answers.filter(
            (_, idx) => idx !== idxToDelete
        );
        props.setAttributes({ answers: newAnswers });

        if (props.attributes.correctAnswer === idxToDelete) {
            props.setAttributes({ correctAnswer: undefined });
        }
    };

    const handleMarkAsCorrect = (index) => {
        props.setAttributes({ correctAnswer: index });
    };

    return (
        <div className="paying-attention-edit-block">
            <TextControl
                label="Question:"
                value={props.attributes.question}
                onChange={updateQuestion}
                style={{ fontSize: "20px" }}
            />
            <p style={{ fontSize: "13px", margin: "20px 0 8px 0" }}>
                Answers:{" "}
            </p>
            {props.attributes.answers.map((answer, index) => (
                <Flex key={index}>
                    <FlexBlock>
                        <TextControl
                            value={answer}
                            onChange={(value) =>
                                handleAnswerChange(value, index)
                            }
                        />
                    </FlexBlock>
                    <FlexItem>
                        <Button
                            className="mark-as-correct-btn"
                            onClick={() => handleMarkAsCorrect(index)}
                        >
                            <Icon
                                className="mark-as-correct"
                                icon={
                                    props.attributes.correctAnswer === index
                                        ? "star-filled"
                                        : "star-empty"
                                }
                            />
                        </Button>
                    </FlexItem>

                    <FlexItem>
                        <Button
                            className="attention-delete"
                            onClick={() => handleDeleteAnswer(index)}
                        >
                            <Icon icon="trash" />
                        </Button>
                    </FlexItem>
                </Flex>
            ))}
            <Button
                variant="primary"
                onClick={() =>
                    props.setAttributes({
                        answers: props.attributes.answers.concat([""]),
                    })
                }
            >
                Add Another Answer
            </Button>
        </div>
    );
}
