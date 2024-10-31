import React from "react";
import InputC from "./UI/input/InputC";
import SelectC from "./UI/select/SelectC";

const PostFilter = ({filter, setFilter}) => {
  return (
    <div>
      <InputC
        value={filter.query}
        onChange={e => setFilter({...filter, query: e.target.value})}
        placeholder="Search post..."
      />
      <SelectC
        value={filter.sort}
        onChange={selectedSort => setFilter({...filter, sort: selectedSort})}
        defaultValue="Sort by"
        options={[
          {value: "title", name:"By title"},
          {value: "content", name:"By content"}
        ]}
      />
    </div>
  );
};

export default PostFilter;
