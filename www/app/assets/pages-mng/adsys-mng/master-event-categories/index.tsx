import React from "react";
import { ContentLayout } from "@/components/Layout/ContentLayout";
import { lazy } from "react";

const Index = lazy(
    () => import("@/features/master-event-categories/components/Index")
);

const Top = () => {
    return (
        <ContentLayout title="観光地・イベントカテゴリ">
            <Index />
        </ContentLayout>
    );
};

export default Top;
