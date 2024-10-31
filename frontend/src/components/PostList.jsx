import React from "react";
import Post from "./Post";

const PostList = ({posts, title, remove}) => {
  return (
    <div>
      <h4 style={{textAlign: "center"}}>{title}</h4>
        {posts.map(post =>
          <Post remove={remove} post={post} key={post.id} />
        )}
    </div>
  );
};

export default PostList;
