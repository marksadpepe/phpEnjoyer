import React from "react";
import ButtonC from "./UI/button/ButtonC";

const Post = (props) => {
  return (
    <div className="post">
      <div className="postContent">
        <strong>{props.post.id}. {props.post.title}</strong>
        <div>{props.post.content}</div>
      </div>
      <div className="postBtns">
        <ButtonC onClick={() => props.remove(props.post.id)} className="deletePost">Delete</ButtonC>
      </div>
    </div>
  );
};

export default Post;
