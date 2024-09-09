import React from "react";
import { ContentLayout } from "@/components/Layout/ContentLayout";
import { lazy } from "react";

const Index = lazy(() => import("@/features/mng-files/components/Index"));

const Top = () => {
    return (
            <ContentLayout title="ファイル">
                <Index />
            </ContentLayout>
    );
};

export default Top;
